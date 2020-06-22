<?php

namespace Elbucho\Library\Controller;
use Psr\Container\ContainerInterface;
use Elbucho\Library\Interfaces\ControllerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class AbstractController implements ControllerInterface
{
    /**
     * Container
     *
     * @access  protected
     * @var     ContainerInterface
     */
    var $container;

    /**
     * Class constructor
     *
     * @access  public
     * @param   ContainerInterface  $container
     * @return  ControllerInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request, Response $response, array $args = []): Response
    {
        switch (strtolower($request->getMethod())) {
            case 'get':
                return $this->get($request, $response, $args);
            case 'post':
                return $this->create($request, $response, $args);
            case 'delete':
                return $this->delete($request, $response, $args);
            case 'patch':
            case 'put':
            case 'update':
                return $this->update($request, $response, $args);
            default:
                return $response->withStatus(400, 'Invalid Request');
        }
    }
}