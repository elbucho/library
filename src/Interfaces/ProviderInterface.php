<?php

namespace Elbucho\Library\Interfaces;

interface ProviderInterface
{
    /**
     * Locate a model by its ID
     *
     * @access  public
     * @param   int     $id
     * @return  ModelInterface|null
     * @throws  \Exception
     */
    public function findById(int $id): ?ModelInterface;

    /**
     * Save a model
     *
     * @access  public
     * @param   ModelInterface  $model
     * @return  void
     */
    public function save(ModelInterface $model);

    /**
     * Hydrate a model with an array of data
     *
     * @access  public
     * @param   array   $data
     * @return  ModelInterface|null
     */
    public function load(array $data = []): ?ModelInterface;
}