<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules;

class BookAuthorModel extends AbstractModel
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
        return $this->{'bookId'} . '_' . $this->{'authorId'};
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
            TableRuleModel::new()->setKey('bookId')
                ->setColumn('book_id')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Number()
                )),
            TableRuleModel::new()->setKey('authorId')
                ->setColumn('author_id')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Number()
                ))
        ]);
    }
}