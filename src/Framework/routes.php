<?php

use Elbucho\Library\Auth\AuthMiddleware;

$container = $app->getContainer();

$app->get('/', function ($request, $response, $args) use ($container) {
    $user = $container->get('auth')->getUser();

    if ( ! is_null($user)) {
        $response->getBody()->write(json_encode(sprintf(
            'Logged in as %s',
            $user->{'username'}
        )));
    } else {
        $response->getBody()->write(json_encode(sprintf(
            'Not logged in: %s',
            print_r($_SESSION, true)
        )));
    }

    return $response;
})->setName('home');

$app->post('/register', function ($request, $response, $args) use ($container) {
    $controller = $container->get('AuthController');
    return $controller->register($request, $response, $args);
})->setName('register');

$app->post('/login', function ($request, $response, $args) use ($container) {
    $controller = $container->get('AuthController');
    return $controller->login($request, $response, $args);
})->setName('login');

$app->any('/logout', function ($request, $response, $args) use ($container) {
    $controller = $container->get('AuthController');
    return $controller->logout($response);
})->setName('logout');

$app->any('/books/{id}', function ($request, $response, $args) use ($container) {
    $controller = $container->get('BookController');
    return $controller->handle($request, $response, $args);
});

$app->group('', function () use ($app, $container) {
    $app->get('/library', function ($request, $response, $args) use ($container) {
        $controller = $container->get('LibraryController');
        return $controller->handle($request, $response, $args);
    });
})->add(new AuthMiddleware($container));