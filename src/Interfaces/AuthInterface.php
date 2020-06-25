<?php

namespace Elbucho\Library\Interfaces;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteContext;

interface AuthInterface
{
    /**
     * Check that the current user has the appropriate privileges to
     * access the given route
     *
     * @access  public
     * @param   void
     * @return  bool
     */
    public function check(): bool;

    /**
     * Return the currently logged-in user
     *
     * @access  public
     * @param   void
     * @return  UserInterface
     */
    public function getUser(): ?UserInterface;

    /**
     * Authenticate a user with a given password
     *
     * @access  public
     * @param   string  $key
     * @param   string  $password
     * @return  bool
     */
    public function login(string $key, string $password): bool;

    /**
     * Log the current user out
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public function logout();

    /**
     * Register a new user
     *
     * @access  public
     * @param   array   $data
     * @return  bool
     */
    public function register(array $data = []): bool;

    /**
     * Load a ContainerInterface into this class
     *
     * @access  public
     * @param   ContainerInterface  $container
     * @return  void
     */
    public function loadContainer(ContainerInterface $container);
}