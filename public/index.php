<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';

use Router\Factories\ConfigFactory;

//Yaml config
//$config = ConfigFactory::YamlConfig();
//$config->addConfigFiles([__DIR__.'/Tests/yaml/admin.yaml']);
//$config->addConfigDir(__DIR__.'/tests/yaml');
//$routes = $config->parseConfig();

//Annotation config
$config = ConfigFactory::AnnotationConfig(dirname(__DIR__) . '/src/Tests/Controllers');
$routes = $config->parseConfig();



//var_dump($routes);
//$router = Router::getRouteCollection($config);
//$router->dispatch();

