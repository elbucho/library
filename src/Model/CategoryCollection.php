<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\ModelInterface;

class CategoryCollection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function isValid(ModelInterface $model): bool
    {
        return $model instanceof CategoryModel;
    }
}