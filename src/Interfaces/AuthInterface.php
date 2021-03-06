<?php

namespace Elbucho\Library\Interfaces;
use Psr\Container\ContainerInterface;
use Elbucho\Library\Model\UserModel;

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
     * @return  UserModel
     */
    public function getUser(): ?UserModel;

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
     * @param   string  $username
     * @param   string  $email
     * @param   string  $password
     * @return  bool
     */
    public function register(string $username, string $email, string $password): bool;

    /**
     * Load a ContainerInterface into this class
     *
     * @access  public
     * @param   ContainerInterface  $container
     * @return  void
     */
    public function loadContainer(ContainerInterface $container);
}