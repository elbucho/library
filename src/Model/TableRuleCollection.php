<?php

namespace Elbucho\Library\Model;

class TableRuleCollection implements \Iterator
{
    /**
     * Table Rule Models array
     *
     * @access  private
     * @var   TableRuleModel[]
     */
    private $rules = [];

    /**
     * Current position
     *
     * @access  private
     * @var   int
     */
    private $pointer;

    /**
     * Constructor
     *
     * @access  public
     * @param   array   $rules
     * @return  TableRuleCollection
     */
    public function __construct(array $rules = [])
    {
        $this->addRules($rules);
        $this->pointer = 0;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->rules[$this->pointer];
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        ++$this->pointer;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return isset($this->rules[$this->pointer]);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * Add an array of rule models to the collection
     *
     * @access  public
     * @param   array   $rules
     * @return  TableRuleCollection
     */
    public function addRules(array $rules = [])
    {
        array_filter($rules, function ($rule) {
            return $rule instanceof TableRuleModel;
        });

        $this->models = array_merge($this->rules, $rules);
        return $this;
    }

    /**
     * Add a single rule to the collection
     *
     * @access  public
     * @param   TableRuleModel  $rule
     * @return  TableRuleCollection
     */
    public function addRule(TableRuleModel $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * Validate all rules in the collection
     *
     * @access  public
     * @param   array   $data
     * @return  bool
     */
    public function isValid(array $data = [])
    {
        /* @var TableRuleModel $rule */
        foreach ($this->rules as $rule) {
            if ( ! array_key_exists($rule->key, $data)) {
                if ($rule->isRequired) {
                    return false;
                }
            }

            if ( ! $rule->rules->validate($data[$rule->key])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if a given key exists in the collection
     *
     * @access  public
     * @param   string  $key
     * @return  bool
     */
    public function keyExists(string $key): bool
    {
        foreach($this->rules as $rule) {
            if (strtolower($rule->key) == strtolower($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return the rule associated with a given key
     *
     * @access  public
     * @param   string  $key
     * @return  TableRuleModel
     */
    public function findByKey(string $key): TableRuleModel
    {
        foreach ($this->rules as $rule) {
            if (strtolower($rule->key) == strtolower($key)) {
                return $rule;
            }
        }

        return null;
    }
}