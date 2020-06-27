<?php

namespace Elbucho\Library\Controller;
use Elbucho\Library\Exceptions\EmailExistsException;
use Elbucho\Library\Exceptions\InvalidEmailException;
use Elbucho\Library\Exceptions\UsernameExistsException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Elbucho\Library\Model\UserProvider;
use Elbucho\Library\Interfaces\AuthInterface;
use Slim\Routing\RouteContext;

class AuthController
{
    /**
     * Container
     *
     * @access  private
     * @var     ContainerInterface
     */
    private $container;

    /**
     * Class constructor
     *
     * @access  public
     * @param   ContainerInterface  $container
     * @return  AuthController
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Handler for the Register POST method
     *
     * @access  public
     * @param   Request     $request
     * @param   Response    $response
     * @param   array       $args
     * @return  Response
     */
    public function register(Request $request, Response $response, array $args = []): Response
    {
        $missingKeys = [];

        foreach (['username','email','password'] as $requiredKey) {
            if ( ! array_key_exists($requiredKey, $_POST)) {
                $missingKeys[] = $requiredKey;
            }
        }

        if ( ! empty($missingKeys)) {
            $response->getBody()->write(json_encode(sprintf(
                'Missing required key(s): %s',
                join(', ', $missingKeys)
            )));

            return $response->withStatus(400);
        }

        try {
            /* @var UserProvider $userProvider */
            $userProvider = $this->container->get('UserProvider');
            $userProvider->create(
                $_POST['username'],
                $_POST['email'],
                $_POST['password']
            );
        } catch (InvalidEmailException $e) {
            $response->getBody()->write(json_encode(sprintf(
                'The email provided is not valid: %s',
                $_POST['email']
            )));

            return $response->withStatus(400);
        } catch (UsernameExistsException $e) {
            $response->getBody()->write(json_encode(sprintf(
                'The username provided already exists: %s',
                $_POST['username']
            )));

            return $response->withStatus(409);
        } catch (EmailExistsException $e) {
            $response->getBody()->write(json_encode(sprintf(
                'The email address provided already exists: %s',
                $_POST['email']
            )));

            return $response->withStatus(409);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(sprintf(
                'Internal Server Error: %s',
                print_r($e->getMessage(), true)
            )));

            return $response->withStatus(500);
        }

        $response->getBody()->write(json_encode('Success'));

        return $response->withStatus(200);
    }

    /**
     * Log the user in
     *
     * @access  public
     * @param   Request     $request
     * @param   Response    $response
     * @param   array       $args
     * @return  Response
     */
    public function login(Request $request, Response $response, array $args = []): Response
    {
        $key = (isset($_POST['key']) ? $_POST['key'] : '');
        $password = (isset($_POST['password']) ? $_POST['password'] : '');

        /* @var AuthInterface $auth */
        $auth = $this->container->get('auth');

        if ($auth->login($key, $password)) {
            $home = RouteContext::fromRequest($request)
                ->getRouteParser()
                ->urlFor('home');

            return $response->withStatus(302)
                ->withHeader('Location', $home);
        }

        return $response->withStatus(401, 'Unauthorized');
    }

    /**
     * Log the user out
     *
     * @access  public
     * @param   Response    $response
     * @return  Response
     */
    public function logout(Response $response): Response
    {
        /* @var AuthInterface $auth */
        $auth = $this->container->get('auth');

        $auth->logout();

        return $response->withStatus(200);
    }
}