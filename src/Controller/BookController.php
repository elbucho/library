<?php

namespace Elbucho\Library\Controller;
use Elbucho\Library\Model\BookCollection;
use Elbucho\Library\Model\BookModel;
use Symfony\Component\HttpFoundation\Response;

class BookController extends AbstractController
{
    /**
     * @inheritDoc
     */
    public function get(array $args = []): Response
    {
        if ( ! empty($args['isbn'])) {
            return new Response($this->getBookByISBN($args)->toJSON(), 200);
        }

        if ( ! empty($args['title'])) {
            return new Response($this->getBooksByTitle($args)->toJSON(), 200);
        }

        if ( ! empty($args['author'])) {
            return new Response($this->getBooksByAuthor($args)->toJSON(), 200);
        }

        if ( ! empty($args['categories'])) {
            return new Response($this->getBooksByCategories($args)->toJSON(), 200);
        }

        return new Response('Invalid Request', 400);
    }

    /**
     * @inheritDoc
     */
    public function create(array $args = []): Response
    {
        if (empty($args['title'])) {
            return new Response('Missing Title', 400);
        }

        $bookData = [
            'title'         => $args['title'],
            'publisher'     => $this->getOrCreatePublisher($args),
            'authors'       => $this->getOrCreateAuthors($args),
            'categories'    => $this->getOrCreateCategories($args)
        ];

        try {
            $bookModel = new BookModel($this->container);
        } catch (\Exception $e) {
            return new Response('Internal Error', 500);
        }

        foreach (['publishedYear', 'isbn', 'pages'] as $optionalKey) {
            if ( ! empty($args[$optionalKey])) {
                $bookData[$optionalKey] = $args[$optionalKey]
            }
        }

        $bookModel->populateModel($bookData);
        $bookModel->save();

        return new Response(
            $bookModel->toJSON(),
            200
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(array $args = []): Response
    {
        if (empty($args['book_id'])) {
            return new Response('Missing Book ID', 400);
        }

        try {
            $bookModel = new BookModel($this->container);
            $bookModel = $bookModel->getById((int) $args['book_id']);
        } catch (\Exception $e) {
            return new Response('Internal Error', 500);
        }

        if ( ! is_null($bookModel)) {
            $bookModel->delete();
        }

        return new Response('Success', 200);
    }

    /**
     * @inheritDoc
     */
    public function update(array $args = []): Response
    {
        // TODO: Implement update() method.

        return new Response('Success', 200);
    }

    /**
     * Find all books based on a given title
     *
     * @access  private
     * @param   array   $args
     * @return  BookCollection
     */
    private function getBooksByTitle(array $args = []): BookCollection
    {
        if (empty($args['title'])) {
            return new BookCollection();
        }

        try {
            $bookModel = new BookModel($this->container);
        } catch (\Exception $e) {
            return new BookCollection();
        }

        return $bookModel->findBooksByTitle($args['title']);
    }

    /**
     * Find all books based on a given Author
     *
     * @access  private
     * @param   array   $args
     * @return  BookCollection
     */
    private function getBooksByAuthor(array $args = []): BookCollection
    {
        if (empty($args['author'])) {
            return new BookCollection();
        }

        $firstName = (empty($args['author']['firstName']) ? '' : $args['author']['firstName']);
        $lastName = (empty($args['author']['lastName']) ? '' : $args['author']['lastName']);

        try {
            $bookModel = new BookModel($this->container);
        } catch (\Exception $e) {
            return new BookCollection();
        }

        return $bookModel->findBooksByAuthor($lastName, $firstName);
    }

    /**
     * Find all books based on a given set of categories
     *
     * @access  private
     * @param   array   $args
     * @return  BookCollection
     */
    private function getBooksByCategories(array $args = []): BookCollection
    {

    }
}