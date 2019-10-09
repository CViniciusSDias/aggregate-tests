<?php

use CViniciusSDias\Aggregate\Application\Answer\AddAnswerCommand;
use CViniciusSDias\Aggregate\Application\Question\AddQuestion;
use CViniciusSDias\Aggregate\Application\Question\AddQuestionCommand;
use CViniciusSDias\Aggregate\Application\Question\QuestionFactory;
use CViniciusSDias\Aggregate\Domain\Answer\Answer;
use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;
use CViniciusSDias\Aggregate\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use CViniciusSDias\Aggregate\Infrastructure\Question\PdoQuestionRepository;
use Doctrine\ORM\Tools\SchemaTool;

require_once 'vendor/autoload.php';

function getRepository(bool $useDoctrine = false): QuestionRepository
{
    $dbPath = ':memory:';
    if (!$useDoctrine) {
        $pdo = new PDO("sqlite:$dbPath");
        $pdo->exec('CREATE TABLE question (id TEXT PRIMARY KEY, prompt TEXT, required INTEGER);');
        $pdo->exec('
            CREATE TABLE answer (
                id TEXT PRIMARY KEY,
                prompt TEXT,
                question_id INTEGER,
                FOREIGN KEY (question_id) REFERENCES question(id)
            );
        ');

        return new PdoQuestionRepository($pdo, new QuestionFactory());
    }

    $entityManager = (new EntityManagerFactory())->createEntityManager([
        'driver' => 'pdo_sqlite',
        'path' => $dbPath,
    ]);
    $schemaTool = new SchemaTool($entityManager);
    $schemaTool->createSchema([
        $entityManager->getClassMetadata(Question::class),
        $entityManager->getClassMetadata(Answer::class),
    ]);
    /** @var QuestionRepository $questionRepository */
    $questionRepository = $entityManager->getRepository(Question::class);
    return $questionRepository;
}

$repository = getRepository(true);

$addQuestion = new AddQuestion($repository, new QuestionFactory());

$firstQuestionAddAnswerCommandList = [
    new AddAnswerCommand('First answer of first question'),
    new AddAnswerCommand('Second answer of first question'),
    new AddAnswerCommand('Third answer of first question'),
];
$addQuestionCommand = new AddQuestionCommand('select_one', 'Question 1 - Select One', true, $firstQuestionAddAnswerCommandList);
$firstQuestionDTO = $addQuestion->execute($addQuestionCommand);

$secondQuestionAddAnswerCommandList = [
    new AddAnswerCommand('First answer of second question'),
    new AddAnswerCommand('Second answer of second question'),
    new AddAnswerCommand('Third answer of second question'),
];
$addQuestionCommand = new AddQuestionCommand('select_multiple', 'Question 2 - Select Multiple', false, $secondQuestionAddAnswerCommandList);
$secondQuestionDTO = $addQuestion->execute($addQuestionCommand);

/* // test code
$firstQuestionId = new QuestionId($firstQuestionDTO->questionId());
$foundQuestion = $repository->ofId($firstQuestionId);
var_dump(
    $foundQuestion->prompt(),
    $foundQuestion->id()->id(),
    $foundQuestion->answers()->map(function (Answer $answer) {
        return $answer->prompt();
    })
);

$answer = $foundQuestion->answers()->first();
$foundQuestion->removeAnswerOfId($answer->id());

$repository->save($foundQuestion);

$foundQuestion = $repository->ofId($firstQuestionId);
var_dump(
    $foundQuestion->prompt(),
    $foundQuestion->id()->id(),
    $foundQuestion->answers()->map(function (Answer $answer) {
        return $answer->prompt();
    })
);
*/