<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Delivery\Web\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AddQuestionFormController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], '<html><body><h1>Add question Form</h1></body></html>');
    }
}
