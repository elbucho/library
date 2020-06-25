<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\ModelInterface;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules;

class AuthorProvider extends AbstractProvider
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
        return 'authors';
    }

    /**
     * Set the table's rules
     *
     * @access  protected
     * @param void
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
                    new Rules\Instance(BookCollection::class)
                ))
        ]);
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
        $bookProvider = new BookAuthorProvider($this->database);

        if (isset($model->{'id'})) {
            $model->{'books'} = $bookProvider->findBooksByAuthorId($model->{'id'});
        }
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
        return AuthorModel::class;
    }
}