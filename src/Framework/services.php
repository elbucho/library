<?php

use Elbucho\Config\Loader\DirectoryLoader;
use Elbucho\Config\Config;
use Elbucho\Database\Database;
use DI\Container;

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

$definitions = $config->get(
    'container.definitions',
    new Config(array())
)->toArray();

$definitions += [
    'config'    => $config,
    'database'  => new Database($config->get('database')),
    '*Model'    => DI\create('Elbucho\Library\Model\*Model')
        ->constructor(DI\get('database')),
    'auth'      => DI\create('Elbucho\Library\Auth\AuthDatabase'),
    'session'   => DI\create('Elbucho\Library\Session\DatabaseSessionHandler')
        ->constructor(DI\get('database'))
];

$builder->addDefinitions($definitions);
$builder->useAnnotations($config->get('container.builder.annotations', false));
$builder->useAutowiring($config->get('container.builder.autowiring', true));
