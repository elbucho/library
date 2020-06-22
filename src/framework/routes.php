<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('Hello world!');
    return $response;
});

$app->get('/books', function (Request $request, Response $response, $args) use ($app) {
    $controller = new \Elbucho\Library\Controller\BookController($app->getContainer());
    return $controller->handle($request, $response, $args);
});