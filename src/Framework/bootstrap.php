<?php

use Slim\Factory\AppFactory;

define('FRAMEWORK_DIR', __DIR__);
define('ROOT_DIR', dirname(dirname(__DIR__)));
define('CONFIG_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'config');
define('LOG_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'logs');
define('VIEW_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'views');
define('VENDOR_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor');

$environment = getenv('LIBRARY_ENV');

define('ENVIRONMENT', ($environment ? $environment : 'dev'));

require_once(VENDOR_DIR . DIRECTORY_SEPARATOR . 'autoload.php');

// Create the Container using PHP-DI
$builder = new DI\ContainerBuilder();
require_once('services.php');
$container = $builder->build();

// Insert the container into the Auth interface
$container->get('auth')->loadContainer($container);

// Bind the container to the application
AppFactory::setContainer($container);
$app = AppFactory::create();

// Set the session handler
session_set_save_handler($container->get('session'));
ini_set('session.hash_function', 1);
ini_set('session.hash_bits_per_character', 5);
session_start();