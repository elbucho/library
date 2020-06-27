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

    /**
     * Return a count of members in this collection
     *
     * @access  public
     * @param   void
     * @return  int
     */
    public function count(): int;

    /**
     * Return an array of all members of collection
     *
     * @access  public
     * @param   void
     * @return  array
     */
    public function toArray(): array;

    /**
     * Return a serialized string of all members of collection in JSON format
     *
     * @access  public
     * @param   void
     * @return  string
     */
    public function toJSON(): string;
}