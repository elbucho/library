<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Elbucho\Library\Auth\AuthMiddleware;

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Hello world!');
    return $response;
});

$app->get('/books/{id}', function (Request $request, Response $response, $args) use ($app) {
    $controller = new \Elbucho\Library\Controller\BookController($app->getContainer());
    return $controller->handle($request, $response, $args);
});

$app->group('', function () use ($app) {
    $app->get('/library', function (Request $request, Response $response, $args) use ($app) {
        $controller = new \Elbucho\Library\Controller\LibraryController($app->getContainer());
        return $controller->handle($request, $response, $args);
    });
})->add(new AuthMiddleware($app->getContainer()));