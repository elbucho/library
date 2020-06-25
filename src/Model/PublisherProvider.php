<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\ModelInterface;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules;

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
            TableRuleModel::new()->setKey('name')
                ->setColumn('name')
                ->setRules(new Rules\AllOf(
                    new Rules\Alpha(',', ' ', '\'', '\"', '.'),
                    new Rules\Length(1, 255, true)
                ))
        ]);
    }

    /**
     * Pull any associated records in for this model
     *
     * @access  protected
     * @param   ModelInterface  $model
     * @return  void
     * @throws  \Exception
     */
    protected function joinForeignKeys(ModelInterface $model)
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