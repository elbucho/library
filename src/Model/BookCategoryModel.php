<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Rules;

class BookCategoryModel extends AbstractModel
{
    /**
     * @inheritDoc
     */
    protected function getTableName(): string
    {
        return 'book_categories';
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    protected function joinForeignKeys(array $data = [])
    {
        return;
    }

    /**
     * @inheritDoc
     */
    public function getIndexKey(): string
    {
        return $this->{'bookId'} . '_' . $this->{'categoryId'};
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
            $query, array($bookId), $this->handle
        );

        $return = new CategoryCollection();

        foreach ($results as $category) {
            $categoryModel = new CategoryModel($this->container);
            $categoryModel->populateModel($category);
            $return->addModel($categoryModel);
        }

        return $return;
    }
}