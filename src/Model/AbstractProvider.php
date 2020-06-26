<?php

namespace Elbucho\Library\Model;
use Elbucho\Database\Database;
use Elbucho\Library\Interfaces\ModelInterface;
use Elbucho\Library\Interfaces\ProviderInterface;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * Database Object
     *
     * @access  protected
     * @var     Database
     */
    protected $database;

    /**
     * Table name
     *
     * @access  protected
     * @var     string
     */
    protected $tableName;

    /**
     * Class constructor
     *
     * @access  public
     * @param   Database    $database
     * @throws  \Exception
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->tableName = $this->getTableName();

        return $this;
    }

    /**
     * Locate a model by its ID
     *
     * @access  public
     * @param   int     $id
     * @return  ModelInterface|null
     * @throws  \Exception
     */
    public function findById(int $id): ?ModelInterface
    {
        $results = $this->database->query(sprintf('
            SELECT
                *
            FROM
                %s
            WHERE
                id = ?
            AND
                deleted_at IS NULL
        ', $this->getTableName()), array($id));

        if (empty($results[0])) {
            return null;
        }

        $model = $this->load($results[0]);

        if (is_null($model)) {
            return null;
        }

        $this->joinForeignKeys($model, $results[0]);

        return $model;
    }

    /**
     * Save a model
     *
     * @access  public
     * @param   AbstractModel   $model
     * @return  void
     */
    public function save(AbstractModel $model)
    {
        if ( ! isset($model->{'id'})) {
            $this->create($model);

            return;
        }

        $columns = [];
        $values = [];

        foreach ($model->toArray() as $key => $value) {
            if ($key == 'id') {
                continue;
            }

            $rule = $model->rules->findModelByKey($key);

            if ( ! $rule instanceof TableRuleModel or ! isset($rule->{'column'})) {
                continue;
            }

            $columns[] = sprintf('%s = ?', $rule->{'column'});
            $values[] = $rule->serialize($value);
        }

        if (empty($columns)) {
            return;
        }

        $values[] = $model->{'id'};
        $this->database->exec(sprintf('
            UPDATE
                %s
            SET
                %s
            WHERE
                id = ?
        ', $this->getTableName(), implode(', ', $columns)));
    }

    /**
     * Hydrate a model with an array of data
     *
     * @access  public
     * @param   array   $data
     * @return  ModelInterface|null
     */
    public function load(array $data = []): ?ModelInterface
    {
        if (empty($data)) {
            return null;
        }

        $modelClass = $this->getClassName();
        return new $modelClass($data);
    }

    /**
     * Insert a new record into the database with the provided info
     *
     * @access  private
     * @param   AbstractModel   $model
     * @return  void
     */
    private function create(AbstractModel $model)
    {
        $columns = [];
        $values = [];

        foreach ($model->toArray() as $key => $value) {
            if ($key == 'id') {
                continue;
            }

            $rule = $model->rules->findModelByKey($key);

            if ( ! $rule instanceof TableRuleModel or ! isset($rule->{'column'})) {
                continue;
            }

            $columns[] = $rule->{'column'};
            $values[] = $rule->serialize($value);
        }

        $valueString = implode(
            ', ',
            array_fill(0, count($columns), '?')
        );

        $query = sprintf('
            INSERT INTO
                %s (
                    %s
                )
            VALUES
                (
                    %s
                )
        ', $this->getTableName(), implode(', ', $columns), $valueString);

        $this->database->exec($query, $values);
        $model->{'id'} = (int) $this->database->getLastInsertId();
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
     * Pull any associated records in for this model
     *
     * @abstract
     * @access  protected
     * @param   ModelInterface  $model
     * @param   array           $args
     * @return  void
     * @throws  \Exception
     */
    protected abstract function joinForeignKeys(ModelInterface $model, array $args = []);

    /**
     * Return the class name of the model this factory provides
     *
     * @abstract
     * @access  protected
     * @param   void
     * @return  string
     */
    protected abstract function getClassName(): string;
}