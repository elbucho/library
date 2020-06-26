<?php

use Elbucho\Config\Loader\DirectoryLoader;
use Elbucho\Config\Config;
use Elbucho\Database\Database;

// Build the Config class to have the correct environment
$environmentPath = CONFIG_DIR . '/environment/' . ENVIRONMENT;

$loader = new DirectoryLoader();
$config = new Config($loader->load(CONFIG_DIR));
$config->remove('environment');

if (is_dir($environmentPath)) {
    $config->append(
        new Config($loader->load($environmentPath))
    );
}

$database = new Database($config->get('database'));
$database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

$definitions = $config->get(
    'container.definitions',
    new Config(array())
)->toArray();

$userProvider = new \Elbucho\Library\Model\UserProvider($database);
$userProvider->setConfig($config);

$definitions += [
    'config'    => $config,
    'database'  => $database,
    '*Provider'    => DI\create('Elbucho\Library\Model\*Provider')
        ->constructor(DI\get('database')),
    'auth'      => DI\create('Elbucho\Library\Auth\AuthDatabase'),
    'session'   => DI\create('Elbucho\Library\Session\DatabaseSessionHandler')
        ->constructor(DI\get('database')),
    'UserProvider'  => $userProvider
];

$builder->addDefinitions($definitions);
$builder->useAnnotations($config->get('container.builder.annotations', false));
$builder->useAutowiring($config->get('container.builder.autowiring', true));