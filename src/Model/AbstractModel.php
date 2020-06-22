<?php

namespace Elbucho\Library\Model;
use Elbucho\Database\Database;
use Elbucho\Library\Interfaces\CollectionInterface;
use Elbucho\Library\Interfaces\ModelInterface;
use Psr\Container\ContainerInterface;
use Respect\Validation\Exceptions\ComponentException;
use Elbucho\Library\Traits\MagicTrait;
use Elbucho\Library\Exceptions\InvalidKeyException;
use Elbucho\Library\Exceptions\InvalidValueException;

abstract class AbstractModel implements ModelInterface
{
    use MagicTrait;

    /**
     * Container Object
     *
     * @access  protected
     * @var     ContainerInterface
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
     * @param   ContainerInterface  $container
     * @throws  \Exception
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->database = $container['database.object'];
        $this->handle = $container['database.handle'];
        $this->tableName = $this->getTableName();
        $this->rules = $this->getRules();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $return = [];

        foreach ($this->data as $key => $value) {
            if ($value instanceof ModelInterface or $value instanceof CollectionInterface) {
                $return[$key] = $value->toArray();
            } elseif ($value instanceof \DateTimeInterface) {
                $return[$key] = $value->format('Y-m-d H:i:s');
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    /**
     * @inheritDoc
     */
    public function toJSON(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Pull a record by ID from the database
     *
     * @access  public
     * @param   int     $id
     * @return  ModelInterface
     * @throws  \Exception
     */
    public function getById(int $id): ?ModelInterface
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
            $rule = $this->rules->findModelByKey($key);

            if ( ! is_null($rule)) {
                $keys[] = $rule->{'column'};
                $values[] = $rule->serialize($value);
            }
        });

        $query = sprintf('
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

        $this->database->exec($query, $values, $this->handle);
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
        if ( ! $this->rules->isDataValid($data)) {
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
        $rule = $this->rules->findModelByKey($key);

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
     * @throws  \Exception
     */
    protected abstract function joinForeignKeys(array $data = []);
}