<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Rules;

class CategoryModel extends AbstractModel
{
    /**
     * @inheritDoc
     */
    protected function getTableName(): string
    {
        return 'categories';
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
            TableRuleModel::new()->setKey('name')
                ->setColumn('name')
                ->setRules(new Rules\AllOf(
                    new Rules\Alpha(',', ' ', '\'', '\"', '.'),
                    new Rules\Length(1, 255, true)
                ))
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function joinForeignKeys(array $data = [])
    {
        return;
    }

    /**
     * @inheritDoc
     */
    public function getIndexKey(): string
    {
        return $this->{'name'};
    }
}