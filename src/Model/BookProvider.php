<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\ModelInterface;

class BookProvider extends AbstractProvider
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
        return 'books';
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
        if (isset($model->{'id'})) {
            $authorProvider = new BookAuthorProvider($this->database);
            $model->{'authors'} = $authorProvider
                ->findAuthorsByBookId($model->{'id'});

            $categoryProvider = new BookCategoryProvider($this->database);
            $model->{'categories'} = $categoryProvider
                ->findCategoriesByBookId($model->{'id'});
        }

        $publisherProvider = new PublisherProvider($this->database);
        $publisher = $publisherProvider->findById((int) $args['publisher_id']);

        if ( ! is_null($publisher)) {
            $model->{'publisher'} = $publisher;
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
        return BookModel::class;
    }

    /**
     * Find book by the title
     *
     * @access  public
     * @param   string  $title
     * @return  ModelInterface|null
     * @throws  \Exception
     */
    public function findByTitle(string $title): ?BookModel
    {
        $results = $this->database->query('
            SELECT
                *
            FROM
                books
            WHERE
                title = ?
        ', array($title));

        if (empty($results[0])) {
            return null;
        }

        return $this->load($results[0]);
    }

    /**
     * Find book by the ISBN
     *
     * @access  public
     * @param   string  $isbn
     * @return  ModelInterface|null
     * @throws  \Exception
     */
    public function findByISBN(string $isbn): ?BookModel
    {
        $results = $this->database->query('
            SELECT
                *
            FROM
                books
            WHERE
                isbn = ?
        ', array($isbn));

        if (empty($results[0])) {
            return null;
        }

        return $this->load($results[0]);
    }

    /**
     * Find books by User ID
     *
     * @access  public
     * @param   int     $userId
     * @param   array   $filters
     * @return  BookCollection
     * @throws  \Exception
     */
    public function findByUserId(int $userId, array $filters = []): BookCollection
    {
        $values = [$userId];

        if (empty($filters)) {
            $conditions = '1 = 1';
        } else {
            $statement = [];

            foreach ($filters as $field => $value) {
                $statement[] = sprintf('B.%s = ?', $field);
                $values[] = $value;
            }

            $conditions = implode(' AND ', $statement);
        }

        $results = $this->database->query(sprintf('
            SELECT
                B.*
            FROM
                user_books AS UB
            INNER JOIN
                books AS B
            ON
                B.id = UB.book_id
            WHERE
                UB.user_id = ?
            AND
                %s
        ', $conditions), $values);

        $books = new BookCollection();

        foreach ($results as $book) {
            $bookModel = $this->load($book);

            if ( ! is_null($bookModel)) {
                $books->addModel($bookModel);
            }
        }

        return $books;
    }
}