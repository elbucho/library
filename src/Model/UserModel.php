<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules;

class UserModel extends AbstractModel
{
    /**
     * Set the table's rules
     *
     * @access  protected
     * @param void
     * @return  TableRuleCollection
     * @throws  ComponentException
     */
    protected function getRules(): TableRuleCollection
    {
        return new TableRuleCollection([
            TableRuleModel::new()->setKey('id')
                ->setColumn('id')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Number()
                )),
            TableRuleModel::new()->setKey('username')
                ->setColumn('username')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\StringType(),
                    new Rules\Length(1, 255, true)
                )),
            TableRuleModel::new()->setKey('email')
                ->setColumn('email')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Email(),
                    new Rules\Length(1, 255, true)
                )),
            TableRuleModel::new()->setKey('passwordHash')
                ->setColumn('password_hash')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\StringType(),
                    new Rules\Length(60, 60, true)
                ))
        ]);
    }

    /**
     * Return the index key for this model
     *
     * @access  public
     * @param void
     * @return  string
     */
    public function getIndexKey(): string
    {
        return $this->{'username'};
    }
}