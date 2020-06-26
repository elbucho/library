<?php

namespace Elbucho\Library\Model;
use Elbucho\Config\Config;
use Elbucho\Database\InvalidConfigException;
use Elbucho\Library\Interfaces\ModelInterface;
use Elbucho\Library\Exceptions\UsernameExistsException;
use Elbucho\Library\Exceptions\EmailExistsException;
use Elbucho\Library\Exceptions\InvalidEmailException;

class UserProvider extends AbstractProvider
{
    /**
     * Config instance
     *
     * @access  private
     * @var     Config
     */
    private $config;

    /**
     * Abstract method for returning table name
     *
     * @access  protected
     * @param void
     * @return  string
     */
    protected function getTableName(): string
    {
        return 'users';
    }

    /**
     * Pull any associated records in for this model
     *
     * @access  protected
     * @param   ModelInterface  $model
     * @param   array           $args
     * @return  void
     * @throws  \Exception
     */
    protected function joinForeignKeys(ModelInterface $model, array $args = [])
    {
        return;
    }

    /**
     * Return the class name of the model this factory provides
     *
     * @access  protected
     * @param void
     * @return  string
     */
    protected function getClassName(): string
    {
        return UserModel::class;
    }

    /**
     * Set the Config instance
     *
     * @access  public
     * @param   Config  $config
     * @return  void
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Create a new user
     *
     * @access  public
     * @param   string  $username
     * @param   string  $email
     * @param   string  $password
     * @return  UserModel
     * @throws  UsernameExistsException
     * @throws  EmailExistsException
     * @throws  InvalidEmailException
     * @throws  InvalidConfigException
     * @throws  \Exception
     */
    public function create(string $username, string $email, string $password): ?UserModel
    {
        if ( ! isset($this->config)) {
            throw new InvalidConfigException('Config not specified for UserProvider');
        }

        if ( ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException(sprintf(
                'Email %s is not valid',
                $email
            ));
        }

        if ($this->usernameExists($username)) {
            throw new UsernameExistsException(sprintf(
                'Username %s already exists',
                $username
            ));
        }

        if ($this->emailExists($email)) {
            throw new EmailExistsException(sprintf(
                'Email %s already exists',
                $email
            ));
        }

        $args = [
            'salt'  => $this->config->get('salt', null),
            'cost'  => $this->config->get('cost', 13)
        ];

        $hash = password_hash($password, PASSWORD_BCRYPT, $args);

        $user = new UserModel([
            'username'  => $username,
            'email'     => $email,
            'password'  => $hash
        ]);

        $this->save($user);

        return $user;
    }

    /**
     * Determine if a given username exists
     *
     * @access  public
     * @param   string  $username
     * @return  bool
     */
    public function usernameExists(string $username): bool
    {
        $results = $this->database->query('
            SELECT
                COUNT(*)
            FROM
                users
            WHERE
                username = ?
        ', array($username));

        return ! empty($results[0]);
    }

    /**
     * Determine if a given email address exists
     *
     * @access  public
     * @param   string  $email
     * @return  bool
     */
    public function emailExists(string $email): bool
    {
        $results = $this->database->query('
            SELECT
                COUNT(*)
            FROM
                users
            WHERE
                email = ?
        ', array($email));

        return ! empty($results[0]);
    }

    /**
     * Find a user by a given key (email / password)
     *
     * @access  public
     * @param   string  $key
     * @return  ModelInterface|null
     */
    public function findByKey(string $key): ?UserModel
    {
        $results = $this->database->query('
            SELECT
                *
            FROM
                users
            WHERE
                email = :key
            UNION SELECT
                *
            FROM
                users
            WHERE
                username = :key
            GROUP BY
                id
        ', ['key' => $key]);

        if (empty($results[0])) {
            return null;
        }

        return $this->load($results[0]);
    }
}