<?php

namespace Elbucho\Library\Model;
use Elbucho\Database\Database;
use Pimple\Container;
use Respect\Validation\Exceptions\ComponentException;
use Elbucho\Library\Traits\MagicTrait;
use Elbucho\Library\Exceptions\InvalidKeyException;
use Elbucho\Library\Exceptions\InvalidValueException;

abstract class AbstractModel
{
    use MagicTrait;

    /**
     * Container Object
     *
     * @access  protected
     * @var     Container
     */
    protected $container;

    /**
     * Database Object
     *
     * @access  protected
     * @var     Database
     */
    protected $database;

    /**
     * Table rules
     *
     * @access  protected
     * @var     TableRuleCollection
     */
    protected $rules;

    /**
     * Table name
     *
     * @access  protected
     * @var     string
     */
    protected $tableName;

    /**
     * Database handle
     *
     * @access  protected
     * @var     string
     */
    protected $handle;

    /**
     * Class constructor
     *
     * @access  public
     * @param   Container   $container
     * @throws  \Exception
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->database = $container['database.object'];
        $this->handle = $container['database.handle'];
        $this->tableName = $this->getTableName();
        $this->rules = $this->getRules();

        return $this;
    }

    /**
     * Pull a record by ID from the database
     *
     * @access  public
     * @param   int     $id
     * @return  AbstractModel
     */
    public function getById(int $id)
    {
        $query = sprintf('
            SELECT
                *
            FROM
                %s
            WHERE
                id = ?
        ', $this->tableName);

        $results = $this->database->query(
            $query, array($id), $this->handle
        );

        if (empty($results[0])) {
            return null;
        }

        $this->populateModel($results[0]);
        $this->joinForeignKeys($results[0]);

        return $this;
    }

    /**
     * Save the record to the database
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public function save()
    {
        $keys = [];
        $values = [];

        array_walk($this->data, function ($value, $key) use ($keys, $values) {
            $rule = $this->rules->findByKey($key);

            if ( ! is_null($rule)) {
                $keys[] = $rule->column;
                $values[] = $rule->serialize($value);
            }
        });

        $query = sprintf(
    '
            INSERT IGNORE INTO
                %s (
                    %s
                )
            VALUES
                (
                    %s
                )
            ',
            $this->tableName,
            join(', ', $keys),
            join(', ', array_fill(0, count($keys), '?'))
        );
    }

    /**
     * Populate this model with information from an array
     *
     * @access  public
     * @param   array   $data
     * @return  void
     */
    public function populateModel(array $data = [])
    {
        if ( ! $this->rules->isValid($data)) {
            return;
        }

        $this->data = array_filter($data, function ($key) {
            return $this->rules->keyExists($key);
        });
    }

    /**
     * Replacement magic setter with validation
     *
     * @access  public
     * @param   string  $key
     * @param   mixed   $value
     * @return  void
     * @throws  InvalidKeyException
     * @throws  InvalidValueException
     */
    public function __set(string $key, $value)
    {
        $rule = $this->rules->findByKey($key);

        if (is_null($rule)) {
            throw new InvalidKeyException(sprintf(
                'The key %s is not configured for this model',
                $key
            ));
        }

        if ( ! $rule->rules->validate($value)) {
            throw new InvalidValueException(sprintf(
                'The provided value does not fit the ruleset for %s',
                $key
            ));
        }

        $this->data[$key] = $value;
    }

    /**
     * Abstract method for returning table name
     *
     * @abstract
     * @access  protected
     * @param   void
     * @return  string
     */
    protected abstract function getTableName(): string;

    /**
     * Set the table's rules
     *
     * @abstract
     * @access  protected
     * @param   void
     * @return  TableRuleCollection
     * @throws  ComponentException
     */
    protected abstract function getRules(): TableRuleCollection;

    /**
     * Pull any associated records in for this model
     *
     * @abstract
     * @access  protected
     * @param   array   $data
     * @return  void
     */
    protected abstract function joinForeignKeys(array $data = []);
}