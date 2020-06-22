<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Rules;

class AuthorModel extends AbstractModel
{
    /**
     * @inheritDoc
     */
    public function getIndexKey(): string
    {
        return strtolower($this->{'lastName'} . '_' . $this->{'firstName'});
    }

    /**
     * @inheritDoc
     */
    protected function getTableName(): string
    {
        return 'authors';
    }

    /**
     * @inheritDoc
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
                    new Rules\Instance('Elbucho\\Library\\BookCollection')
                ))
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function joinForeignKeys(array $data = [])
    {
        $authorModel = new BookAuthorModel($this->container);
        $this->{'books'} = $authorModel->findBooksByAuthorId((int) $this->{'id'});
    }
}