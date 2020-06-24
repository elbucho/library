<?php

namespace Elbucho\Library\Auth;
use Elbucho\Library\Framework\Middleware;
use Elbucho\Library\Interfaces\AuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

class AuthMiddleware extends Middleware
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        /* @var AuthInterface $auth */
        $auth = $this->container->get('auth');
        $route = RouteContext::fromRequest($request);

        if ($auth->check($route)) {
            return $handler->handle($request);
        }

        $factory = new ResponseFactory();

        return $factory->createResponse(401)
            ->withHeader(
                'WWW-Authenticate',
                'Basic realm="Protected"'
            );
    }
}