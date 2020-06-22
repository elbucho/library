<?php

namespace Elbucho\Library\Interfaces;
use Symfony\Component\HttpFoundation\Response;

interface ControllerInterface
{
    /**
     * Handler for the GET method
     *
     * @access  public
     * @param   array   $args
     * @return  Response
     */
    public function get(array $args = []): Response;

    /**
     * Handler for the POST method
     *
     * @access  public
     * @param   array   $args
     * @return  Response
     */
    public function create(array $args = []): Response;

    /**
     * Handler for the DELETE method
     *
     * @access  public
     * @param   array   $args
     * @return  Response
     */
    public function delete(array $args = []): Response;

    /**
     * Handler for the PUT / PATCH methods
     *
     * @access  public
     * @param   array   $args
     * @return  Response
     */
    public function update(array $args = []): Response;

    /**
     * Default handler for a given request
     *
     * @access  public
     * @param   array   $args
     * @return  Response
     */
    public function handle(array $args = []): Response;
}