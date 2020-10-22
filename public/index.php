<?php
require_once '../vendor/autoload.php';

use Router\Factories\ConfigFactory;

$config = ConfigFactory::YamlConfig();
$config->addConfigFiles([dirname(__DIR__).'/tests/admin.yaml']);
$config->addConfigDir(dirname(__DIR__).'/tests/');
$config->addConfigDir(dirname(__DIR__).'/tests2/');
$routes = $config->parseConfig();
var_dump($routes->getRoutes());die;
//var_dump(dirname(__DIR__).'/tests/rotes.yaml');
//$config = ConfigFactory::PhpConfig(['/path/to/file']);
//$config = ConfigFactory::SimpleRoute();

//$router = Router::getRouteCollection($config);
//$router->dispatch();

