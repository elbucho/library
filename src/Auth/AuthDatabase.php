<?php

namespace Elbucho\Library\Auth;
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
     * Currently logged-in user
     *
     * @access  private
     * @var     UserInterface
     */
    private $user = null;

    /**
     * Check that the current user has the appropriate privileges to
     * access the given route
     *
     * @access  public
     * @param   void
     * @return  bool
     * @throws  \Exception
     */
    public function check(): bool
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
        /* @var \Elbucho\Library\Model\UserModel $userModel */
        $userModel = $this->container->get('UserModel');
        $user = $userModel->findByKey($key);

        if (is_null($user)) {
            return false;
        }

        if (password_verify($password, $user->{'passwordHash'})) {
            $this->user = $user;
            $_SESSION['user'] = (int) $user->getIndexKey();

            return true;
        }

        return false;
    }

    public function logout()
    {
        $this->user = null;
        session_destroy();
    }

    /**
     * Load a ContainerInterface into this class
     *
     * @access  public
     * @param   ContainerInterface  $container
     * @return  void
     */
    public function loadContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Register a new user
     *
     * @access  public
     * @param array $data
     * @return  bool
     */
    public function register(array $data = []): bool
    {
        // TODO: Implement register() method.
    }
}