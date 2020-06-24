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

var_dump($container->make('Elbucho\Library\Model\BookModel'));die;

// Bind the container to the application
AppFactory::setContainer($container);
$app = AppFactory::create();
