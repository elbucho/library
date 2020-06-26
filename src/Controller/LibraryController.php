<?php

namespace Elbucho\Library\Controller;

use Elbucho\Library\Model\AuthorCollection;
use Elbucho\Library\Model\CategoryCollection;
use Elbucho\Library\Model\UserBookModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LibraryController extends AbstractController
{
    /**
     * Handler for the GET method
     *
     * @access  public
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return  Response
     */
    public function get(Request $request, Response $response, array $args = []): Response
    {
        $params = $request->getQueryParams();

        $filters = array_filter($params, function ($key) {
            return array_key_exists($key, ['isbn','title']);
        }, ARRAY_FILTER_USE_KEY);

        $userModel = $this->container->get('auth')->getUser();
        $books = $this->container->get('BookProvider')->findByUserId(
            $userModel->{'id'},
            $filters
        );

        $response->getBody()->write($books->toJSON());
        return $response->withStatus(200);
    }

    /**
     * Handler for the POST method
     *
     * @access  public
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return  Response
     */
    public function create(Request $request, Response $response, array $args = []): Response
    {
        $params = $request->getQueryParams();

        if (empty($params['book_id'])) {
            $bookModel = $this->createBook($params, $response);
            $params['book_id'] = $bookModel->{'id'};
        }

        return $this->addBookToCollection((int) $params['book_id'], $response);
    }

    /**
     * Handler for the DELETE method
     *
     * @access  public
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return  Response
     */
    public function delete(Request $request, Response $response, array $args = []): Response
    {
        // TODO: Implement delete() method.
    }

    /**
     * Handler for the PUT / PATCH methods
     *
     * @access  public
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return  Response
     */
    public function update(Request $request, Response $response, array $args = []): Response
    {
        // TODO: Implement update() method.
    }

    /**
     * Add an existing book to the user's library
     *
     * @access  private
     * @param   int         $bookId
     * @param   Response    $response
     * @return  Response
     */
    private function addBookToCollection(int $bookId, Response $response): Response
    {
        $userModel = $this->container->get('auth')->getUser();

        try {
            $userBookModel = new UserBookModel([
                'bookId'    => $bookId,
                'userId'    => $userModel->{'id'}
            ]);

            $this->container->get('UserBookProvider')->save($userBookModel);

            $response->getBody()->write(json_encode('Success'));
            return $response->withStatus(200);
        } catch (\PDOException $e) {
            return $response->withStatus(400, 'Invalid Request');
        }
    }

    /**
     * Create a new book model
     *
     * @access  private
     * @param   array       $params
     * @param   Response    $response
     * @return  Response
     */
    private function createBook(array $params, Response $response): Response
    {
        $requiredKeys = ['authors','publisher','published_year','title'];
        $missingKeys = [];

        foreach ($requiredKeys as $key) {
            if (empty($params[$key])) {
                $missingKeys[] = $key;
            }
        }

        if ( ! empty($missingKeys)) {
            $response->getBody()->write(json_encode(sprintf(
                'Missing the following keys: %s',
                implode(',', $missingKeys)
            )));

            return $response->withStatus(400, 'Invalid Request');
        }

        try {
            $publisherModel = $this->container->get('PublisherModel');
            $publisher = $publisherModel->findByName($params['publisher']);

            if (is_null($publisher)) {
                $publisherModel->{'name'} = $params['publisher'];
                $publisher = $publisherModel->save();
            }

            $authorCollection = new AuthorCollection();

            foreach ($params['authors'] as $name) {
                $authorModel = $this->container->get('AuthorModel');
                $author = $authorModel->findByName($name['last'], $name['first']);

                if (is_null($author)) {
                    $authorModel->{'firstName'} = $name['first'];
                    $authorModel->{'lastName'} = $name['last'];
                    $author = $authorModel->save();
                }

                $authorCollection->addModel($author);
            }

            $categoryCollection = new CategoryCollection();

            if (!empty($params['categories'])) {
                foreach ($params['categories'] as $name) {
                    $categoryModel = $this->container->get('CategoryModel');
                    $category = $categoryModel->findByName($name);

                    if (is_null($category)) {
                        $categoryModel->{'name'} = $name;
                        $category = $categoryModel->save();
                    }

                    $categoryCollection->addModel($category);
                }
            }

            $bookModel = $this->container->get('BookModel');
            $bookModel->{'title'} = $params['title'];
            $bookModel->{'publishedYear'} = $params['published_year'];
            $bookModel->{'authors'} = $authorCollection;
            $bookModel->{'categories'} = $categoryCollection;
            $bookModel->{'publisher'} = $publisher;

            if (!empty($params['isbn'])) {
                $bookModel->{'isbn'} = $params['isbn'];
            }

            if (!empty($params['pages'])) {
                $bookModel->{'pages'} = (int)$params['pages'];
            }

            $bookModel->save();
        } catch (\Exception $e) {
            return $response->withStatus(400, 'Invalid Request');
        }

        $response->getBody()->write(json_encode('Success'));
        return $response->withStatus(200);
    }
}