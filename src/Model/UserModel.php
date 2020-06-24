<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\UserInterface;
use Respect\Validation\Exceptions\ComponentException;

class UserModel extends AbstractModel implements UserInterface
{
    protected function getTableName(): string
    {
        return 'users';
    }

    protected function getRules(): TableRuleCollection
    {
        // TODO: Implement getRules() method.
    }

    protected function joinForeignKeys(array $data = [])
    {
        // TODO: Implement joinForeignKeys() method.
    }

    public function getIndexKey(): string
    {
        // TODO: Implement getIndexKey() method.
    }
}