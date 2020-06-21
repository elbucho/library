<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Rules\AllOf;

class TableRuleModel
{
    /**
     * Key name
     *
     * @access  public
     * @var   string
     */
    public $key;

    /**
     * Column name
     *
     * @access  public
     * @var   string
     */
    public $column;

    /**
     * Validation Rules
     *
     * @access  public
     * @var   AllOf
     */
    public $rules;

    /**
     * Required key
     *
     * @access  public
     * @var   bool
     */
    public $isRequired = false;

    /**
     * Constructor
     *
     * @access  public
     * @param   void
     * @return  TableRuleModel
     */
    public function __construct()
    {
        $this->setRules(new AllOf());

        return $this;
    }

    /**
     * Static initializer
     *
     * @static
     * @access  public
     * @param   void
     * @return  TableRuleModel
     */
    static public function new(): TableRuleModel
    {
        return new TableRuleModel();
    }

    /**
     * Setter for the index key
     *
     * @access  public
     * @param   string  $key
     * @return  TableRuleModel
     */
    public function setKey(string $key): TableRuleModel
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Setter for the database column
     *
     * @access  public
     * @param   string  $column
     * @return  TableRuleModel
     */
    public function setColumn(string $column): TableRuleModel
    {
        $this->column = $column;

        return $this;
    }

    /**
     * Make this key required
     *
     * @access  public
     * @param   void
     * @return  TableRuleModel
     */
    public function setRequired(): TableRuleModel
    {
        $this->isRequired = true;

        return $this;
    }

    /**
     * Setter for this column's rules
     *
     * @access  public
     * @param   AllOf   $rules
     * @return  TableRuleModel
     */
    public function setRules(AllOf $rules): TableRuleModel
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Serialize the data associated with this rule for insertion into a database
     *
     * @access  public
     * @param   mixed   $data
     * @return  string
     */
    public function serialize($data): string
    {
        if ($data instanceof AbstractModel) {
            return $data->{'id'};
        }

        if ($data instanceof \DateTime) {
            return $data->format('Y-m-d H:i:s');
        }

        if (is_numeric($data)) {
            return (string) $data;
        }

        if (is_bool($data)) {
            if ($data === true) {
                return "1";
            }

            return "0";
        }

        return $data;
    }
}