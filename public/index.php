<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';

use Router\Factories\ConfigFactory;

//YAML CONFIG
$config = ConfigFactory::YamlConfig();
//$config->addConfigFiles([dirname(__DIR__).'/tests/admin.yaml']);
$config->addConfigDir(dirname(__DIR__).'/tests/yaml');
//$config->addConfigDir(dirname(__DIR__).'/tests2/yaml');
$routes = $config->parseConfig();
var_dump($routes);


//$router = Router::getRouteCollection($config);
//$router->dispatch();

