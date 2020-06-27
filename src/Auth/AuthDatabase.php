<?php

namespace Elbucho\Library\Auth;
use Elbucho\Database\InvalidConfigException;
use Elbucho\Library\Exceptions\EmailExistsException;
use Elbucho\Library\Exceptions\InvalidEmailException;
use Elbucho\Library\Exceptions\UsernameExistsException;
use Elbucho\Library\Interfaces\AuthInterface;
use Elbucho\Library\Model\UserModel;
use Elbucho\Library\Model\UserProvider;
use Psr\Container\ContainerInterface;

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
     * @var     UserModel
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
        $this->loadUser();

        if (is_null($this->user)) {
            return false;
        }

        $userId = (isset($_SESSION['user']) ? $_SESSION['user'] : null);

        if (is_null($userId)) {
            return false;
        }

        if ((int) $userId !== (int) $this->user->{'id'}) {
            $this->logout();

            return false;
        }

        return true;
    }

    /**
     * Return the currently logged-in user
     *
     * @access  public
     * @param   void
     * @return  UserModel
     */
    public function getUser(): ?UserModel
    {
        if ( ! isset($this->user)) {
            $this->loadUser();
        }

        return $this->user;
    }

    public function login(string $key, string $password): bool
    {
        /* @var UserProvider $userProvider */
        $userProvider = $this->container->get('UserProvider');
        $user = $userProvider->findByKey($key);

        if (is_null($user)) {
            return false;
        }

        if (password_verify($password, $user->{'passwordHash'})) {
            $this->user = $user;
            $_SESSION['user'] = (int) $user->{'id'};

            return true;
        }

        return false;
    }

    public function logout()
    {
        $this->user = null;
        session_destroy();

        die;
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
     * @param   string  $username
     * @param   string  $email
     * @param   string  $password
     * @return  bool
     * @throws  UsernameExistsException
     * @throws  EmailExistsException
     * @throws  InvalidEmailException
     * @throws  InvalidConfigException
     * @throws  \Exception
     */
    public function register(string $username, string $email, string $password): bool
    {
        /* @var UserProvider $userProvider */
        $userProvider = $this->container->get('UserProvider');
        $userModel = $userProvider->create($username, $email, $password);

        return ! is_null($userModel);
    }

    /**
     * Load the user into this class
     *
     * @access  private
     * @param   void
     * @return  void
     */
    private function loadUser()
    {
        if (isset($this->user)) {
            return;
        }

        $userId = (isset($_SESSION['user']) ? $_SESSION['user'] : null);

        if (is_null($userId)) {
            return;
        }

        /* @var UserProvider $userProvider */
        $userProvider = $this->container->get('UserProvider');
        $this->user = $userProvider->findById($userId);
    }
}