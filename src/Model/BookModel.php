<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules;

class BookModel extends AbstractModel
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
        return strtolower($this->{'title'}) . '_' . $this->{'publisher_id'};
    }

    /**
     * Set the table's rules
     *
     * @access  protected
     * @param   void
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
            TableRuleModel::new()->setKey('publisher')
                ->setColumn('publisher_id')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Instance(PublisherModel::class)
                )),
            TableRuleModel::new()->setKey('title')
                ->setColumn('title')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Alnum(':', '.', '\'', '\"', ' ', '_'),
                    new Rules\Length(1, 255, true)
                )),
            TableRuleModel::new()->setKey('isbn')
                ->setColumn('isbn')
                ->setRules(new Rules\AllOf(
                    new Rules\Number()
                )),
            TableRuleModel::new()->setKey('publishedYear')
                ->setColumn('published_year')
                ->setRules(new Rules\AllOf(
                    new Rules\Date('Y'),
                    new Rules\Max(date('Y'))
                )),
            TableRuleModel::new()->setKey('pages')
                ->setColumn('pages')
                ->setRules(new Rules\AllOf(
                    new Rules\Number(),
                    new Rules\Min(1)
                )),
            TableRuleModel::new()->setKey('authors')
                ->setRules(new Rules\AllOf(
                    new Rules\Instance(AuthorCollection::class)
                )),
            TableRuleModel::new()->setKey('categories')
                ->setRules(new Rules\AllOf(
                    new Rules\Instance(CategoryCollection::class)
                ))
        ]);
    }
}