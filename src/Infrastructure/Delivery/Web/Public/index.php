<?php

use CViniciusSDias\Aggregate\Infrastructure\Delivery\Web\Controller\AddQuestionFormController;
use CViniciusSDias\Aggregate\Infrastructure\DI\ContainerCreator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Server\RequestHandlerInterface;

require_once __DIR__ . '/../../../../../vendor/autoload.php';

$routeList = [
    'GET /questions/add' => AddQuestionFormController::class,
    'POST /questions/add' => '',
    'GET /questions/remove' => '',
];

$path = filter_input(INPUT_SERVER, 'PATH_INFO');
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
$routeKey = "$method $path";

if (!isset($routeList[$routeKey])) {
    http_response_code(404);
    exit();
}

$controllerClass = $routeList[$routeKey];
$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
);
$serverRequest = $creator->fromGlobals();

$container = ContainerCreator::instance();

/** @var RequestHandlerInterface $controllerInstance */
$controllerInstance = $container->get($controllerClass);
$response = $controllerInstance->handle($serverRequest);
foreach ($response->getHeaders() as $header => $values) {
    foreach ($values as $value) {
        header(sprintf('%s: %s', $header, $value), false);
    }
}

echo $response->getBody();
