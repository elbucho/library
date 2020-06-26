<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\ModelInterface;

class BookAuthorProvider extends AbstractProvider
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
        return 'book_authors';
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
        return BookAuthorModel::class;
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
            $query, array($authorId)
        );

        $return = new BookCollection();
        $bookProvider = new BookProvider($this->database);

        foreach ($results as $book) {
            $bookModel = $bookProvider->load($book);

            if ( ! is_null($bookModel)) {
                $return->addModel($bookModel);
            }
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
            $query, array($bookId)
        );

        $return = new AuthorCollection();
        $authorProvider = new AuthorProvider($this->database);

        foreach ($results as $author) {
            $authorModel = $authorProvider->load($author);

            if ( ! is_null($authorModel)) {
                $return->addModel($authorModel);
            }
        }

        return $return;
    }
}