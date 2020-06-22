<?php

namespace Elbucho\Library\Controller;
use Pimple\Container;
use Elbucho\Library\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController implements ControllerInterface
{
    /**
     * Container
     *
     * @access  protected
     * @var     Container
     */
    var $container;

    /**
     * Class constructor
     *
     * @access  public
     * @param   Container   $container
     * @return  ControllerInterface
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function handle(array $args = []): Response
    {
        /* @var Request $request */
        $request = $this->container['http.request'];

        switch (strtolower($request->getMethod())) {
            case 'get':
                return $this->get($args);
            case 'post':
                return $this->create($args);
            case 'delete':
                return $this->delete($args);
            case 'patch':
            case 'put':
                return $this->update($args);
            default:
                return new Response('Invalid Request', 400);
        }
    }
}