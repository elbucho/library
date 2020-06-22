<?php

namespace Elbucho\Library\Interfaces;

interface CollectionInterface extends \Iterator
{
    /**
     * Add a model to the collection
     *
     * @access  public
     * @param   ModelInterface  $model
     * @return  CollectionInterface
     */
    public function addModel(ModelInterface $model): CollectionInterface;

    /**
     * Add a collection to the collection
     *
     * @access  public
     * @param   Iterable    $collection
     * @return  CollectionInterface
     */
    public function addCollection(Iterable $collection): CollectionInterface;

    /**
     * Find model by key
     *
     * @access  public
     * @param   string  $key
     * @return  ModelInterface
     */
    public function findModelByKey(string $key): ?ModelInterface;

    /**
     * Determine if a given key is in the collection
     *
     * @access  public
     * @param   string  $key
     * @return  bool
     */
    public function keyExists(string $key): bool;
}