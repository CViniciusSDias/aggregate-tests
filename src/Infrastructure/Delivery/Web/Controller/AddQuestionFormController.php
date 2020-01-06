<?php

namespace CViniciusSDias\Aggregate\Infrastructure\Delivery\Web\Controller;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

class AddQuestionFormController implements RequestHandlerInterface
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], $this->twig->render('add-question-form.html.twig', []));
    }
}
