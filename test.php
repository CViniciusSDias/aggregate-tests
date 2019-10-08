<?php

use CViniciusSDias\Aggregate\Domain\Answer\Answer;
use CViniciusSDias\Aggregate\Domain\Answer\AnswerId;
use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionId;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;
use CViniciusSDias\Aggregate\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use CViniciusSDias\Aggregate\Infrastructure\Question\PdoQuestionRepository;
use Doctrine\ORM\Tools\SchemaTool;

require_once 'vendor/autoload.php';

function getRepository(bool $useDoctrine = false): QuestionRepository
{
    if (!$useDoctrine) {
        $pdo = new PDO('sqlite::memory:');
        $pdo->exec('CREATE TABLE question (id TEXT PRIMARY KEY, prompt TEXT);');
        $pdo->exec('CREATE TABLE answer (id TEXT PRIMARY KEY, prompt TEXT, question_id INTEGER, FOREIGN KEY (question_id) REFERENCES question(id));');

        return new PdoQuestionRepository($pdo);
    }

    $entityManager = (new EntityManagerFactory())->createEntityManager([
        'driver' => 'pdo_sqlite',
        'path' => ':memory:',
    ]);
    $schemaTool = new SchemaTool($entityManager);
    $schemaTool->createSchema([
        $entityManager->getClassMetadata(Question::class),
        $entityManager->getClassMetadata(Answer::class),
    ]);
    return $entityManager->getRepository(Question::class);
}

$repository = getRepository(true);

$firstQuestionId = new QuestionId();
$aQuestion = new Question($firstQuestionId, 'Question 1');

$aQuestion->addAnswer(new AnswerId(), 'First answer')
    ->addAnswer(new AnswerId(), 'Second answer');


$secondQuestionId = new QuestionId();
$anotherQuestion = new Question($secondQuestionId, 'Question 2');

$answerIdToRemove = new AnswerId();
$anotherQuestion->addAnswer($answerIdToRemove, 'Another answer')
    ->addAnswer(new AnswerId(), 'Yet another answer')
    ->addAnswer(new AnswerId(), 'One more answer');

$repository->save($aQuestion);
$repository->save($anotherQuestion);

$foundQuestion = $repository->ofId($firstQuestionId);
var_dump(
    $foundQuestion->prompt(),
    $foundQuestion->id()->id(),
    $foundQuestion->answers()->map(function (Answer $answer) { return $answer->prompt(); })
);

$anotherQuestion->removeAnswerOfId($answerIdToRemove);

$repository->save($anotherQuestion);

$foundQuestion = $repository->ofId($secondQuestionId);
var_dump(
    $foundQuestion->prompt(),
    $foundQuestion->id()->id(),
    $foundQuestion->answers()->map(function (Answer $answer) { return $answer->prompt(); })
);
