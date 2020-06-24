<?php

namespace Elbucho\Library\Framework;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class Middleware implements MiddlewareInterface
{
    /**
     * Container instance
     *
     * @access  protected
     * @var     ContainerInterface
     */
    protected $container;

    /**
     * Class constructor
     *
     * @access  public
     * @param   ContainerInterface  $container
     * @return  MiddlewareInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Invoke magic method
     *
     * @access  public
     * @param   ServerRequestInterface  $request
     * @param   RequestHandlerInterface $handler
     * @return  ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        return $this->process($request, $handler);
    }
}