<?php

namespace Elbucho\Library\Controller;
use Elbucho\Library\Model\BookCollection;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BookController extends AbstractController
{
    /**
     * @inheritDoc
     */
    public function get(Request $request, Response $response, array $args = []): Response
    {
        $params = $request->getQueryParams();

        if ( ! empty($params['isbn'])) {
            $book = $this->container->get('BookProvider')->findByISBN($params['isbn']);

            if (is_null($book)) {
                $response->getBody()->write(json_encode('Book Not Found'));
                return $response->withStatus(404);
            }

            $response->getBody()->write($book->toJSON());
            return $response->withStatus(200);
        }

        if ( ! empty($params['title'])) {
            $response->getBody()->write($this->getBooksByTitle($params)->toJSON());
            return $response->withStatus(200);
        }

        if ( ! empty($params['author'])) {
            $response->getBody()->write($this->getBooksByAuthor($params)->toJSON());
            return $response->withStatus(200);
        }

        if ( ! empty($params['categories'])) {
            $response->getBody()->write($this->getBooksByCategories($params)->toJSON());
            return $response->withStatus(200);
        }

        return $response->withStatus(400, 'Invalid Request');
    }

    /**
     * @inheritDoc
     */
    public function create(Request $request, Response $response, array $args = []): Response
    {
/*        if (empty($args['title'])) {
            return $response->withStatus(400, 'Missing Title');
        }

        $bookData = [
            'title'         => $args['title'],
            'publisher'     => $this->getOrCreatePublisher($args),
            'authors'       => $this->getOrCreateAuthors($args),
            'categories'    => $this->getOrCreateCategories($args)
        ];

        try {
            $bookModel = $this->container->get('BookModel');
        } catch (\Exception $e) {
            return $response->withStatus(500, 'Internal Error');
        }

        foreach (['publishedYear', 'isbn', 'pages'] as $optionalKey) {
            if ( ! empty($args[$optionalKey])) {
                $bookData[$optionalKey] = $args[$optionalKey];
            }
        }

        $bookModel->populateModel($bookData);
        $bookModel->save();

        $response->getBody()->write($bookModel->toJSON()); */
        return $response->withStatus(200);
    }

    /**
     * @inheritDoc
     */
    public function delete(Request $request, Response $response, array $args = []): Response
    {
/*        if (empty($args['book_id'])) {
            return $response->withStatus(400, 'Missing Book ID');
        }

        try {
            $bookModel = new BookModel($this->container);
            $bookModel = $bookModel->getById((int) $args['book_id']);
        } catch (\Exception $e) {
            return $response->withStatus(500, 'Internal Error');
        }

        if ( ! is_null($bookModel)) {
            $bookModel->delete();
        } */

        $response->getBody()->write(json_encode('Success'));
        return $response->withStatus(200);
    }

    /**
     * @inheritDoc
     */
    public function update(Request $request, Response $response, array $args = []): Response
    {
        // TODO: Implement update() method.

        $response->getBody()->write(json_encode('Success'));
        return $response->withStatus(200);
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
//        if (empty($args['title'])) {
            return new BookCollection();
/*        }

        try {
            $bookModel = new BookModel($this->container);
        } catch (\Exception $e) {
            return new BookCollection();
        }

        return $bookModel->findBooksByTitle($args['title']); */
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
//        if (empty($args['author'])) {
            return new BookCollection();
/*        }

        $firstName = (empty($args['author']['firstName']) ? '' : $args['author']['firstName']);
        $lastName = (empty($args['author']['lastName']) ? '' : $args['author']['lastName']);

        try {
            $bookModel = new BookModel($this->container);
        } catch (\Exception $e) {
            return new BookCollection();
        }

        return $bookModel->findBooksByAuthor($lastName, $firstName); */
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
//        if (empty($args['categories'])) {
            return new BookCollection();
/*        }

        try {
            $bookModel = new BookModel($this->container);
        } catch (\Exception $e) {
            return new BookCollection();
        }

        return $bookModel->findBooksByCategories($args['categories']); */
    }
}