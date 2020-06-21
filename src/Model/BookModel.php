<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Rules;


class BookModel extends AbstractModel
{
    /**
     * @inheritDoc
     */
    protected function getTableName(): string
    {
        return 'books';
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
            TableRuleModel::new()->setKey('publisher')
                ->setColumn('publisher_id')
                ->setRules(new Rules\AllOf(
                    new Rules\Instance('Elbucho\\Library\\Model\\PublisherModel')
                )),
            TableRuleModel::new()->setKey('title')
                ->setColumn('title')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Alnum(),
                    new Rules\Length(1, 255)
                )),
            TableRuleModel::new()->setKey('isbn')
                ->setColumn('isbn')
                ->setRules(new Rules\AllOf(
                    new Rules\Number()
                )),
            TableRuleModel::new()->setKey('publishedYear')
                ->setColumn('published_year')
                ->setRules(new Rules\AllOf(
                    new Rules\Date('Y'),
                    new Rules\Max(date('Y'))
                )),
            TableRuleModel::new()->setKey('pages')
                ->setColumn('pages')
                ->setRules(new Rules\AllOf(
                    new Rules\Number(),
                    new Rules\Min(1)
                ))
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function joinForeignKeys(array $data = [])
    {
        $authorModel = new BookAuthorModel($this->container);
        $authors = $authorModel->findByBookId((int) $this->{'id'});

        if ( ! is_null($authors)) {
            $this->{'authors'} = $authors;
        }

        $categoryModel = new BookCategoryModel($this->container);
        $categories = $categoryModel->findByBookId((int) $this->{'id'});

        if ( ! is_null($categories)) {
            $this->{'categories'} = $categories;
        }

        if ( ! empty($data['publisher_id'])) {
            $publisherModel = new PublisherModel($this->container);
            $publisher = $publisherModel->findById((int) $data['publisher_id']);

            if ( ! is_null($publisher)) {
                $this->{'publisher'} = $publisher;
            }
        }
    }

    /**
     * Find book by the title
     *
     * @access  public
     * @param   string  $title
     * @return  BookModel
     */
    public function findByTitle(string $title): BookModel
    {
        $query = sprintf('
            SELECT
                *
            FROM
                %s
            WHERE
                title = ?
        ', $this->tableName);

        $results = $this->database->query(
            $query, array($title), $this->handle
        );

        if (empty($results[0])) {
            return null;
        }

        $this->populateModel($results);
        $this->joinForeignKeys();

        return $this;
    }

    /**
     * Find book by the ISBN
     *
     * @access  public
     * @param   string  $isbn
     * @return  BookModel
     */
    public function findByISBN(string $isbn): BookModel
    {
        $query = sprintf('
            SELECT
                *
            FROM
                %s
            WHERE
                isbn = ?
        ', $this->tableName);

        $results = $this->database->query(
            $query, array($isbn), $this->handle
        );

        if (empty($results[0])) {
            return null;
        }

        $this->populateModel($results);
        $this->joinForeignKeys();

        return $this;
    }
}