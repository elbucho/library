<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules;

class AuthorModel extends AbstractModel
{
    /**
     * Return the index key for this model
     *
     * @access  public
     * @param   void
     * @return  string
     */
    public function getIndexKey(): string
    {
        return strtolower($this->{'lastName'} . '_' . $this->{'firstName'});
    }

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
                ->setRules(new Rules\AllOf(
                    new Rules\Number()
                )),
            TableRuleModel::new()->setKey('firstName')
                ->setColumn('first_name')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Alpha('-', '\'', '.', ' '),
                    new Rules\Length(1, 255, true)
                )),
            TableRuleModel::new()->setKey('lastName')
                ->setColumn('last_name')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Alpha('-', '\'', '.', ' '),
                    new Rules\Length(1, 255, true)
                )),
            TableRuleModel::new()->setKey('books')
                ->setRules(new Rules\AllOf(
                    new Rules\Instance(BookCollection::class)
                ))
        ]);
    }
}