<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\ModelInterface;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules;

class BookCategoryProvider extends AbstractProvider
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
        return 'book_categories';
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
            TableRuleModel::new()->setKey('categoryId')
                ->setColumn('category_id')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Number()
                )),
            TableRuleModel::new()->setKey('bookId')
                ->setColumn('book_id')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Number()
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
        return;
    }

    /**
     * Return the class name of the model this factory provides
     *
     * @access  protected
     * @param   void
     * @return  string
     */
    protected function getClassName(): string
    {
        return BookCategoryModel::class;
    }

    /**
     * Return the categories associated with a given Book ID
     *
     * @access  public
     * @param   int     $bookId
     * @return  CategoryCollection
     * @throws  \Exception
     */
    public function findCategoriesByBookId(int $bookId): CategoryCollection
    {
        $query = sprintf('
            SELECT
                C.*
            FROM
                book_categories AS BC
            INNER JOIN
                categories as C
            ON
                C.id = BC.category_id
            WHERE
                BC.book_id = ?
        ');

        $results = $this->database->query(
            $query, array($bookId)
        );

        $return = new CategoryCollection();
        $categoryProvider = new CategoryProvider($this->database);

        foreach ($results as $category) {
            $categoryModel = $categoryProvider->load($category);

            if ( ! is_null($categoryModel)) {
                $return->addModel($categoryModel);
            }
        }

        return $return;
    }
}