<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';

use Router\Cache\RouterFileCache;
use Router\Config\Config;
use Router\Factories\LoaderFactory;
use Router\Loader\AnnotationDirectoryLoader;
use Router\Loader\YamlDirectoryLoader;
use Router\Router;

//Yaml config
//$loader = new YamlDirectoryLoader();
//$config = ConfigFactory::getConfig($loader);
//$config->addRoutesDir(dirname(__DIR__) . '/src/Tests/yaml');
//$routes = $config->parseConfig();


//Annotation config
//$loader = new AnnotationDirectoryLoader();
//$cache = new RouterFileCache('/tmp/cache/router/');
//$loader->enableCache($cache, false);

$config = new Config();
$config->setEnvMode(Config::DEBUG);
$config->enableCache(new RouterFileCache(dirname($_SERVER['DOCUMENT_ROOT']) . '/cache/router/', 'routes'));

$loader = LoaderFactory::getLoader(new AnnotationDirectoryLoader(), $config) ;
$loader->addDir(dirname(__DIR__) . '/src/Tests/Controllers');

$router = new Router($loader);
$router->matchRequest();

//var_dump($routes);die;
