<?php

namespace Elbucho\Library\Interfaces;
use Elbucho\Library\Interfaces\UserInterface;
use Slim\Routing\RouteContext;

interface AuthInterface
{
    /**
     * Check that the current user has the appropriate privileges to
     * access the given route
     *
     * @access  public
     * @param   RouteContext    $currentRoute
     * @return  bool
     */
    public function check(RouteContext $currentRoute): bool;

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
}