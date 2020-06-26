<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\ModelInterface;

class PublisherProvider extends AbstractProvider
{

    /**
     * Abstract method for returning table name
     *
     * @access  protected
     * @param void
     * @return  string
     */
    protected function getTableName(): string
    {
        return 'publishers';
    }

    /**
     * Pull any associated records in for this model
     *
     * @access  protected
     * @param   ModelInterface  $model
     * @param   array           $args
     * @return  void
     * @throws  \Exception
     */
    protected function joinForeignKeys(ModelInterface $model, array $args = [])
    {
        return;
    }

    /**
     * Return the class name of the model this factory provides
     *
     * @access  protected
     * @param void
     * @return  string
     */
    protected function getClassName(): string
    {
        return PublisherModel::class;
    }
}