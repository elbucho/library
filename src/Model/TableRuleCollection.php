<?php

namespace Elbucho\Library\Model;

use Elbucho\Library\Interfaces\ModelInterface;

class TableRuleCollection extends AbstractCollection
{
    /**
     * Constructor
     *
     * @access  public
     * @param   array   $rules
     * @return  TableRuleCollection
     */
    public function __construct(array $rules = [])
    {
        $this->addCollection($rules);

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function isValid(ModelInterface $model): bool
    {
        return $model instanceof TableRuleModel;
    }

    /**
     * Validate the rules in this collection against the provided data
     *
     * @access  public
     * @param   array   $data
     * @return  bool
     */
    public function isDataValid(array $data = []): bool
    {
        /* @var TableRuleModel $rule */
        foreach ($this->models as $rule) {
            if ( ! array_key_exists($rule->{'key'}, $data)) {
                if ($rule->isRequired) {
                    return false;
                }
            }

            if ( ! $rule->rules->validate($data[$rule->{'key'}])) {
                return false;
            }
        }

        return true;
    }
}