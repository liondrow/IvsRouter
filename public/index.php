<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';

use IvsRouter\Cache\RouterFileCache;
use IvsRouter\Config\Config;
use IvsRouter\Factories\LoaderFactory;
use IvsRouter\Loader\AnnotationDirectoryLoader;
use IvsRouter\Loader\YamlDirectoryLoader;
use IvsRouter\Router;

$config = new Config();
$config->setEnvMode(Config::DEBUG);
$config->enableCache(new RouterFileCache(dirname($_SERVER['DOCUMENT_ROOT']) . '/cache/router/', 'routes'));

//Yaml config
$loader = LoaderFactory::getLoader(new YamlDirectoryLoader(), $config);
$loader->addDir(dirname($_SERVER['DOCUMENT_ROOT']) . '/src/Tests/yaml');

//Annotation config
//$loader = LoaderFactory::getLoader(new AnnotationDirectoryLoader(), $config) ;
//$loader->addDir(dirname(__DIR__) . '/src/Tests/Controllers');

$router = new Router($loader);
$router->matchRequest();
