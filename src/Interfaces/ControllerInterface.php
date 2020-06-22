<?php

namespace Elbucho\Library\Interfaces;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface ControllerInterface
{
    /**
     * Handler for the GET method
     *
     * @access  public
     * @param   Request     $request
     * @param   Response    $response
     * @param   array       $args
     * @return  Response
     */
    public function get(Request $request, Response $response, array $args = []): Response;

    /**
     * Handler for the POST method
     *
     * @access  public
     * @param   Request     $request
     * @param   Response    $response
     * @param   array       $args
     * @return  Response
     */
    public function create(Request $request, Response $response, array $args = []): Response;

    /**
     * Handler for the DELETE method
     *
     * @access  public
     * @param   Request     $request
     * @param   Response    $response
     * @param   array       $args
     * @return  Response
     */
    public function delete(Request $request, Response $response, array $args = []): Response;

    /**
     * Handler for the PUT / PATCH methods
     *
     * @access  public
     * @param   Request     $request
     * @param   Response    $response
     * @param   array       $args
     * @return  Response
     */
    public function update(Request $request, Response $response, array $args = []): Response;

    /**
     * Default handler for a given request
     *
     * @access  public
     * @param   Request     $request
     * @param   Response    $response
     * @param   array       $args
     * @return  Response
     */
    public function handle(Request $request, Response $response, array $args = []): Response;
}