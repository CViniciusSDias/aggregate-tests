<?php

use CViniciusSDias\Aggregate\Application\Factory\QuestionFactory;
use CViniciusSDias\Aggregate\Application\Persistence\TransactionalSession;
use CViniciusSDias\Aggregate\Application\Service\Answer\AddAnswerCommand;
use CViniciusSDias\Aggregate\Application\Service\Question\AddQuestion;
use CViniciusSDias\Aggregate\Application\Service\Question\AddQuestionCommand;
use CViniciusSDias\Aggregate\Application\Service\TransactionalApplicationService;
use CViniciusSDias\Aggregate\Domain\Answer\Answer;
use CViniciusSDias\Aggregate\Domain\Question\Question;
use CViniciusSDias\Aggregate\Domain\Question\QuestionRepository;
use CViniciusSDias\Aggregate\Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use CViniciusSDias\Aggregate\Infrastructure\Persistence\DoctrineSession;
use CViniciusSDias\Aggregate\Infrastructure\Persistence\PdoSession;
use CViniciusSDias\Aggregate\Infrastructure\Domain\Question\InMemoryQuestionRepository;
use CViniciusSDias\Aggregate\Infrastructure\Domain\Question\PdoQuestionRepository;
use Doctrine\ORM\Tools\SchemaTool;

require_once 'vendor/autoload.php';

function getConnection(bool $useDoctrine = false)
{
    $dbPath = ':memory:';
    // $dbPath = __DIR__ . '/db.sqlite';
    if (!$useDoctrine) {
        $pdo = new PDO("sqlite:$dbPath");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE IF NOT EXISTS question (id TEXT PRIMARY KEY, prompt TEXT, required INTEGER, question_type INTEGER);');
        $pdo->exec('
            CREATE TABLE IF NOT EXISTS answer (
                id TEXT PRIMARY KEY,
                prompt TEXT,
                question_id INTEGER,
                FOREIGN KEY (question_id) REFERENCES question(id)
            );
        ');

        return $pdo;
    }

    $entityManager = (new EntityManagerFactory())->createEntityManager([
        'driver' => 'pdo_sqlite',
        'path' => $dbPath,
    ]);
    try {
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema([
            $entityManager->getClassMetadata(Question::class),
            $entityManager->getClassMetadata(Answer::class),
        ]);
    } catch (\Throwable $e) { }

    return $entityManager;
}

function getRepository(bool $useDoctrine = false): QuestionRepository
{
    $conn = getConnection($useDoctrine);
    if (!$useDoctrine) {
        return new PdoQuestionRepository($conn, new QuestionFactory());
    }

    return $conn->getRepository(Question::class);
}

function getSession(bool $useDoctrine = false): TransactionalSession
{
    $conn = getConnection($useDoctrine);

    if (!$useDoctrine) {
        return new PdoSession($conn);
    }

    return new DoctrineSession($conn);
}

// $repository = new InMemoryQuestionRepository();
$useDoctrine = true;
$repository = getRepository($useDoctrine);

$addQuestion = new AddQuestion($repository, new QuestionFactory());
$addQuestionWithinTransaction = new TransactionalApplicationService($addQuestion, getSession($useDoctrine));

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