<?php
require_once '../vendor/autoload.php';

use Router\Factories\ConfigFactory;

//YAML CONFIG
$config = ConfigFactory::YamlConfig();
$config->addConfigFiles([dirname(__DIR__).'/tests/yaml/admin.yaml']);
$config->addConfigDir(dirname(__DIR__).'/tests/yaml');
$config->addConfigDir(dirname(__DIR__).'/tests2/yaml');
$routes = $config->parseConfig();
var_dump($routes);


//$router = Router::getRouteCollection($config);
//$router->dispatch();

