<?php

namespace Elbucho\Library\Auth;
use Elbucho\Database\Database;
use Elbucho\Library\Interfaces\AuthInterface;
use Elbucho\Library\Interfaces\UserInterface;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteContext;

class AuthDatabase implements AuthInterface
{
    /**
     * Container interface
     *
     * @access  private
     * @var     ContainerInterface
     */
    private $container;

    /**
     * Database object
     *
     * @access  private
     * @var     Database
     */
    private $database;

    /**
     * Currently logged-in user
     *
     * @access  private
     * @var     UserInterface
     */
    private $user;

    /**
     * Class Constructor
     *
     * @access  public
     * @param   ContainerInterface  $container
     * @return  AuthInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->database = $container->get('database');

        return $this;
    }

    /**
     * Check that the current user has the appropriate privileges to
     * access the given route
     *
     * @access  public
     * @param   RouteContext    $currentRoute
     * @return  bool
     * @throws  \Exception
     */
    public function check(RouteContext $currentRoute): bool
    {
        $userId = (isset($_SESSION['user']) ? $_SESSION['user'] : null);

        if (is_null($userId)) {
            return false;
        }

        if (isset($this->user)) {
            if ((int) $userId !== (int) $this->user->getIndexKey()) {
                $this->logout();

                return false;
            }

            return true;
        }

        /* @var \Elbucho\Library\Model\UserModel $userModel */
        $userModel = $this->container->get('UserModel');
        $this->user = $userModel->getById($userId);

        return ! is_null($this->user);
    }

    /**
     * Return the currently logged-in user
     *
     * @access  public
     * @param   void
     * @return  UserInterface
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function login(string $key, string $password): bool
    {
        // TODO: Implement login() method.
    }

    public function logout()
    {
        // TODO: Implement logout() method.
    }
}