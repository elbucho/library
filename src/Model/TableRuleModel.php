<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\ModelInterface;
use Respect\Validation\Rules\AllOf;
use Elbucho\Library\Traits\MagicTrait;

class TableRuleModel implements ModelInterface
{
    use MagicTrait;

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
        $this->{'isRequired'} = false;

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
        $this->{'key'} = $key;

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
        $this->{'column'} = $column;

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
        $this->{'isRequired'} = true;

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
        $this->{'rules'} = $rules;

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
        if ($data instanceof ModelInterface) {
            return $data->{'id'};
        }

        if ($data instanceof \DateTimeInterface) {
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

    /**
     * @inheritDoc
     */
    public function getIndexKey(): string
    {
        return $this->{'key'};
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $return = [];

        foreach ($this->data as $key => $value) {
            if (is_object($value)) {
                $return[$key] = json_encode($value);
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
     * Determine whether this model is valid
     *
     * @access  public
     * @param   void
     * @return  bool
     */
    public function isValid(): bool
    {
        return true;
    }
}