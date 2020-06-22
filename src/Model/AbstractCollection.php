<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\CollectionInterface;
use Elbucho\Library\Interfaces\ModelInterface;
use Elbucho\Library\Traits\CollectionTrait;

abstract class AbstractCollection implements CollectionInterface
{
    use CollectionTrait;

    /**
     * @inheritDoc
     */
    public function addModel(ModelInterface $model): CollectionInterface
    {
        if ($this->isValid($model)) {
            $this->models[$model->getIndexKey()] = $model;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addCollection(iterable $collection): CollectionInterface
    {
        foreach ($collection as $model) {
            if ( ! $model instanceof ModelInterface) {
                continue;
            }

            $this->addModel($model);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function findModelByKey(string $key): ?ModelInterface
    {
        if ( ! empty($this->models[$key])) {
            return $this->models[$key];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function keyExists(string $key): bool
    {
        return ! empty($this->models[$key]);
    }

    /**
     * Validator for this model type
     *
     * @abstract
     * @access  protected
     * @param   ModelInterface  $model
     * @return  bool
     */
    abstract protected function isValid(ModelInterface $model): bool;
}