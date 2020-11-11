<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IvsRouter\Loader;

use Doctrine\Common\Annotations\AnnotationReader;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use ReflectionClass;
use ReflectionMethod;
use RegexIterator;
use IvsRouter\Route;
use IvsRouter\RouteCollection;
use IvsRouter\Config\Config;
use IvsRouter\Interfaces\Cache;
use IvsRouter\Interfaces\LoaderInterface;
use ReflectionException;
use IvsRouter\Exceptions\BadConfigConfigurationException;
use IvsRouter\Exceptions\ResourceNotFoundException;

/**
 * Class AnnotationDirectoryLoader
 *
 * @package IvsRouter\Loader
 */
class AnnotationDirectoryLoader implements LoaderInterface
{

    /**
     * @var AnnotationReader 
     */
    private $reader;

    /**
     * @var RouteCollection 
     */
    private $routeCollection;

    /**
     * @var Config 
     */
    private $config;

    /**
     * @var Cache 
     */
    private $cache;

    /**
     * AnnotationDirectoryLoader constructor.
     */
    public function __construct(RouteCollection $routeCollection = null)
    {
        $this->reader = new AnnotationReader();
        $this->routeCollection = $routeCollection ?? new RouteCollection();
    }

    /**
     * @param  Config $config
     * @return void
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
        if($this->config->isCacheEnabled()) {
            $this->cache = $this->config->getCache();
            if($this->config->getEnvMode() == Config::DEBUG) {
                $this->cache->clearCache();
            }
        }
    }

    /**
     * @param  string $dir
     * @return void
     */
    public function addDir(string $dir): void
    {
        if(!is_dir($dir)) {
            throw new ResourceNotFoundException("Directory $dir does not exist!");
        }

        if($this->config->isCacheEnabled() && $this->config->getEnvMode() == Config::PRODUCTION) {
            if($this->getFromCache($dir)) {
                return;
            }
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $regex    = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        foreach ($regex as $file => $value) {
            $current = $this->parseTokens(token_get_all(file_get_contents(str_replace('\\', '/', $file))));
            if ($current !== false) {
                list($namespace, $class) = $current;
                try {
                    $refClass = new ReflectionClass($namespace . $class);
                } catch (ReflectionException $e) {
                    echo $e->getMessage();
                }
                if ($refClass->isAbstract()) {
                    continue;
                }
                $refMethods = $refClass->getMethods(ReflectionMethod::IS_PUBLIC);
                $routes = [];
                foreach($refMethods as $method)
                {
                    $annotation = $this->reader->getMethodAnnotation($method, Route::class);
                    if($annotation) {
                        if($annotation instanceof Route) {
                            $annotation->setTarget($refClass->getName() . "@" . $method->getName());
                        }
                        $routes[] = $annotation;
                    }
                }
                $this->routeCollection->addRoutesArray($routes);
                if($this->config->isCacheEnabled()) {
                    $this->cacheRoutes($routes, $dir);
                }
            } else {
                throw new ResourceNotFoundException("Suitable for the configuration classes were not found");
            }
        }
    }

    /**
     * @return RouteCollection
     */
    public function fetchRoutes(): RouteCollection
    {
        if(empty($this->routeCollection->getRoutes())) {
            throw new BadConfigConfigurationException("No available routes found");
        }
        return $this->routeCollection;
    }

    /**
     * @param  string $dir
     * @return bool
     */
    private function getFromCache(string $dir): bool
    {
        $cache = $this->cache->get();
        if(!empty($cache['data'])) {
            if(array_key_exists($dir, $cache['data'])) {
                $this->routeCollection->addRoutesArray($cache['data'][$dir]);
                return true;
            }
        }
        return false;
    }

    /**
     * @param  array  $routes
     * @param  string $dir
     * @return void
     */
    private function cacheRoutes(array $routes, string $dir): void
    {
        $cacheData = [$dir => $routes];
        $cache = $this->cache->get();
        if(!empty($cache['data'])) {
            $this->cache->append($cacheData);
        } else {
            $this->cache->save($cacheData);
        }
    }

    /**
     * @param  array $tokens
     * @return array|false
     */
    private function parseTokens(array $tokens)
    {
        $nsStart    = false;
        $classStart = false;
        $namespace  = '';
        foreach ($tokens as $token) {
            if ($token[0] === T_CLASS) {
                $classStart = true;
            }
            if ($classStart && $token[0] === T_STRING) {
                return [$namespace, $token[1]];
            }
            if ($token[0] === T_NAMESPACE) {
                $nsStart = true;
            }
            if ($nsStart && $token[0] === ';') {
                $nsStart = false;
            }
            if ($nsStart && $token[0] === T_STRING) {
                $namespace .= $token[1] . '\\';
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routeCollection->getRoutes();
    }
}
