<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Delivery\Web\Controller;

use CViniciusSDias\Aggregate\Application\Service\Answer\AddAnswerCommand;
use CViniciusSDias\Aggregate\Application\Service\Question\AddQuestion;
use CViniciusSDias\Aggregate\Application\Service\Question\AddQuestionCommand;
use CViniciusSDias\Aggregate\Application\Service\TransactionalApplicationService;
use CViniciusSDias\Aggregate\Infrastructure\Persistence\DoctrineSession;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PersistQuestionController implements RequestHandlerInterface
{
    private TransactionalApplicationService $transaction;

    public function __construct(DoctrineSession $transactionSession, AddQuestion $addQuestion)
    {
        $this->transaction = new TransactionalApplicationService($addQuestion, $transactionSession);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();
        $answerPromptList = array_map(fn(string $answerPrompt) => new AddAnswerCommand($answerPrompt), $parsedBody['answerPrompt']);

        $addQuestionCommand = new AddQuestionCommand(
            $parsedBody['questionType'],
            $parsedBody['prompt'],
            array_key_exists('required', $parsedBody),
            $answerPromptList
        );
        $this->transaction->execute($addQuestionCommand);

        return new Response(200, [], '<html><body><h1>Add question Form</h1></body></html>');
    }
}
