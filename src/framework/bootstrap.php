<?php

define('FRAMEWORK_DIR', __DIR__);
define('ROOT_DIR', dirname(dirname(__DIR__)));
define('CONFIG_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'config');
define('LOG_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'logs');
define('VIEW_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'views');
define('VENDOR_DIR', ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor');

require_once(VENDOR_DIR . DIRECTORY_SEPARATOR . 'autoload.php');

// Create the Container using PHP-DI
$container = new \DI\Container();
require_once('services.php');

// Bind the container to the application
\Slim\Factory\AppFactory::setContainer($container);
$app = \Slim\Factory\AppFactory::create();
