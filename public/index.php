<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';

use Router\Factories\ConfigFactory;
use Router\Loader\AnnotationDirectoryLoader;
use Router\Loader\YamlDirectoryLoader;
use Router\Router;

//Yaml config
//$loader = new YamlDirectoryLoader();
//$config = ConfigFactory::getConfig($loader);
//$config->addRoutesDir(dirname(__DIR__) . '/src/Tests/yaml');
//$routes = $config->parseConfig();


//Annotation config
$loader = new AnnotationDirectoryLoader();
$config = ConfigFactory::getConfig($loader);
$config->addRoutesDir(dirname(__DIR__) . '/src/Tests/Controllers');
$routes = $config->parseConfig();

$router = new Router($routes);
$router->matchRequest();
