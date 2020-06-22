<?php

namespace Elbucho\Library\Model;
use Respect\Validation\Rules;

class BookAuthorModel extends AbstractModel
{
    /**
     * @inheritDoc
     */
    public function getIndexKey(): string
    {
        return $this->{'bookId'} . '_' . $this->{'authorId'};
    }

    /**
     * @inheritDoc
     */
    protected function getTableName(): string
    {
        return 'book_authors';
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
            TableRuleModel::new()->setKey('bookId')
                ->setColumn('book_id')
                ->setRequired()
                ->setRules(new Rules\AllOf(
                    new Rules\Number()
                )),
            TableRuleModel::new()->setKey('authorId')
                ->setColumn('author_id')
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
     * Return a collection of books written by the given Author ID
     *
     * @access  public
     * @param   int     $authorId
     * @return  BookCollection
     * @throws  \Exception
     */
    public function findBooksByAuthorId(int $authorId): BookCollection
    {
        $query = sprintf('
            SELECT
                B.*
            FROM
                book_authors AS BA
            INNER JOIN
                books AS B
            ON
                B.id = BA.book_id
            WHERE
                BA.author_id = ?
        ');

        $results = $this->database->query(
            $query, array($authorId), $this->handle
        );

        $return = new BookCollection();

        foreach ($results as $book) {
            $bookModel = new BookModel($this->container);
            $bookModel->populateModel($book);
            $return->addModel($bookModel);
        }

        return $return;
    }

    /**
     * Return a collection of authors for a book for a given Book ID
     *
     * @access  public
     * @param   int     $bookId
     * @return  AuthorCollection
     * @throws  \Exception
     */
    public function findAuthorsByBookId(int $bookId): AuthorCollection
    {
        $query = sprintf('
            SELECT
                A.*
            FROM
                book_authors AS BA
            INNER JOIN
                authors AS A
            ON
                A.id = BA.author_id
            WHERE
                BA.book_id = ?
        ');

        $results = $this->database->query(
            $query, array($bookId), $this->handle
        );

        $return = new AuthorCollection();

        foreach ($results as $author) {
            $authorModel = new AuthorModel($this->container);
            $authorModel->populateModel($author);
            $return->addModel($authorModel);
        }

        return $return;
    }
}