<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Domain\Question;

use CViniciusSDias\Aggregate\Application\Factory\QuestionFactory;
use CViniciusSDias\Aggregate\Domain\Answer\Answer;
use CViniciusSDias\Aggregate\Domain\Answer\AnswerId;
use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;
use CViniciusSDias\Aggregate\Domain\Question\SelectMultiple;
use CViniciusSDias\Aggregate\Domain\Question\SelectOne;
use PDO;
use SplObjectStorage;

class PdoQuestionRepository implements QuestionRepository
{
    private PDO $pdo;
    private SplObjectStorage $fetchedAnswers;
    private QuestionFactory $questionFactory;
    private const QUESTION_TYPES = [
        SelectOne::class => 1,
        SelectMultiple::class => 2,
    ];

    public function __construct(PDO $pdo, QuestionFactory $questionFactory)
    {
        $this->pdo = $pdo;
        $this->questionFactory = $questionFactory;
        $this->fetchedAnswers = new SplObjectStorage();
    }

    public function ofId(QuestionId $questionId): Question
    {
        $stm = $this->pdo->prepare('SELECT * FROM question WHERE id = ?');
        $stm->bindValue(1, $questionId->id());
        $stm->execute();

        $row = $stm->fetch(PDO::FETCH_ASSOC);
        $question = $this->questionFactory
            ->createQuestion($row['question_type'], new QuestionId($row['id']), $row['prompt'], $row['required']);

        $stm = $this->pdo->prepare('SELECT * FROM answer WHERE question_id = ?');
        $stm->bindValue(1, $questionId->id());
        $stm->execute();

        $answerDataList = $stm->fetchAll(PDO::FETCH_ASSOC);

        foreach ($answerDataList as $answerData) {
            $question->addAnswer(new AnswerId($answerData['id']), $answerData['prompt']);
        }
        $this->fetchedAnswers->attach($questionId, $question->answers());

        return $question;
    }

    public function save(Question $question): Question
    {
        if (!$this->hasWithId($question->id())) {
            return $this->insertQuestion($question);
        }

        return $this->updateQuestion($question);
    }

    private function hasWithId(QuestionId $id): bool
    {
        $stm = $this->pdo->prepare('SELECT COUNT(id) FROM question WHERE id = ?');
        $stm->bindValue(1, $id);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_COLUMN) == 1;
    }

    private function insertQuestion(Question $question): Question
    {
        $stm = $this->pdo->prepare('INSERT INTO question VALUES (?, ?, ?, ?);');
        $stm->bindValue(1, $question->id());
        $stm->bindValue(2, $question->prompt());
        $stm->bindValue(3, $question->isRequired(), PDO::PARAM_BOOL);
        $stm->bindValue(4, PdoQuestionRepository::QUESTION_TYPES[get_class($question)], PDO::PARAM_INT);
        $stm->execute();

        $this->insertAnswers($question);
        $this->fetchedAnswers->attach($question->id(), $question->answers());

        return $question;
    }

    private function insertAnswers(Question $question): void
    {
        $stm = $this->pdo->prepare('INSERT INTO answer VALUES (?, ?, ?)');
        $stm->bindValue(3, $question->id());
        foreach ($question->answers() as $answer) {
            $stm->bindValue(1, $answer->id());
            $stm->bindValue(2, $answer->prompt());
            $stm->execute();
        }
    }

    private function updateQuestion(Question $question): Question
    {
        $this->removeDeletedAnswers($question);
        $this->updateExistingAnswers($question);

        $stm = $this->pdo->prepare('UPDATE question SET prompt = ? WHERE id = ?');
        $stm->bindValue(1, $question->prompt(), PDO::PARAM_INT);
        $stm->bindValue(2, $question->id());

        return $question;
    }

    private function removeDeletedAnswers(Question $question): void
    {
        $answerIds = $this->fetchedAnswers[$question->id()]->toArray();

        $validAnswersIds = $question->answers()
            ->map(function (Answer $answer) {
                return $answer->id();
            })
            ->toArray();
        $answerIdsToDelete = array_diff($answerIds, $validAnswersIds);

        $idsPlaceholder = implode(',', array_fill(0, count($answerIdsToDelete), '?'));

        $stm = $this->pdo->prepare("DELETE FROM answer WHERE id IN ($idsPlaceholder)");
        $stm->execute($answerIdsToDelete);
    }

    private function updateExistingAnswers(Question $question): void
    {
        foreach ($question->answers() as $answer) {
            $stm = $this->pdo->prepare('UPDATE answer SET prompt = ? WHERE id = ?');
            $stm->bindValue(1, $answer->prompt());
            $stm->bindValue(2, $answer->id());
            $stm->execute();
        }
    }
}
