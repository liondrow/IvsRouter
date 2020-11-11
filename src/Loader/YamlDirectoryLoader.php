<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Loader;


use FilesystemIterator;
use RegexIterator;
use Router\Config\Config;
use Router\Exceptions\BadConfigConfigurationException;
use Router\Exceptions\BadRouteConfigurationException;
use Router\Exceptions\ResourceNotFoundException;
use Router\Interfaces\Cache;
use Router\Interfaces\LoaderInterface;
use Router\RouteCollection;

/**
 * Class YamlDirectoryLoader
 * @package Router\Loader
 */
class YamlDirectoryLoader implements LoaderInterface
{
    /** @var array */
    private array $configFiles = [];

    /** @var RouteCollection */
    private $routeCollection;

    /** @var Config */
    private $config;

    /** @var Cache */
    private $cache;

    /** @var string */
    private $yamlDir = '';

    /**
     * AnnotationDirectoryLoader constructor.
     */
    public function __construct(RouteCollection $routeCollection = null)
    {
        $this->routeCollection = $routeCollection ?? new RouteCollection();
    }

    /**
     * @param Config $config
     * @return void
     */
    public function setConfig(Config $config): void
    {
        $this->config = $config;
        if($this->config->isCacheEnabled()){
            $this->cache = $this->config->getCache();
            if($this->config->getEnvMode() == Config::DEBUG){
                $this->cache->clearCache();
            }
        }
    }

    /**
     * @param string $dir
     */
    public function addDir(string $dir): void
    {
        if(!is_dir($dir)){
            throw new ResourceNotFoundException("Directory $dir does not exist!");
        }

        if($this->config->isCacheEnabled() && $this->config->getEnvMode() == Config::PRODUCTION){
            if($this->getFromCache($dir)) return;
        }

        $iterator = new FilesystemIterator($dir);
        $filter = new RegexIterator($iterator, '/^.*\.(yaml)$/i');
        $files = [];
        foreach($filter as $entry){
            $files[] = $entry->getPathname();
        }
        if(!empty($files)){
            $this->addRouteFiles($files);
            $this->yamlDir = $dir;
        }
    }

    /**
     * @param array $files
     */
    public function addRouteFiles(array $files): void
    {
        if(empty($files)){
            throw new BadRouteConfigurationException('No configuration files specified!');
        }
        $this->configFiles = array_merge($this->configFiles, $files);
        if(empty($this->configFiles)){
            throw new BadConfigConfigurationException("Configuration is empty");
        }
        foreach($this->configFiles as $configFile){
            if(!is_file($configFile)){
                throw new ResourceNotFoundException("File $configFile does not exist!");
            }
            $this->parseYamlFile($configFile);
        }
    }

    /**
     * @param string $filename
     */
    private function parseYamlFile(string $filename):void
    {
        $fileRoutes = yaml_parse_file($filename);
        foreach($fileRoutes['routes'] as $routeName => $fileRoute){
            $routes = [];
            if(isset($fileRoutes['prefix'])) {
                $fileRoute[0] = $fileRoutes['prefix'] . $fileRoute[0];
            }
            if(isset($fileRoutes['name_prefix'])) {
                $routeName = $fileRoutes['name_prefix'].$routeName;
            }
            $routes[$routeName] = $fileRoute;
            $this->routeCollection->addRoutesFromArray($routes);
        }
    }

    /**
     * @return RouteCollection
     */
    public function fetchRoutes(): RouteCollection
    {
        if(empty($this->routeCollection->getRoutes())){
            throw new BadConfigConfigurationException("No available routes found");
        }
        if($this->config->isCacheEnabled()) {
            $this->cacheRoutes($this->routeCollection->getRoutes(), $this->yamlDir);
        }
        return $this->routeCollection;
    }

    /**
     * @param string $dir
     * @return bool
     */
    private function getFromCache(string $dir): bool
    {
        $cache = $this->cache->get();
        if(!empty($cache['data'])){
            if(array_key_exists($dir, $cache['data'])){
                $this->routeCollection->addRoutesArray($cache['data'][$dir]);
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $routes
     * @param string $dir
     * @return void
     */
    private function cacheRoutes(array $routes, string $dir): void
    {
        $cacheData = [$dir => $routes];
        $cache = $this->cache->get();
        if(!empty($cache['data'])){
            $this->cache->append($cacheData);
        } else {
            $this->cache->save($cacheData);
        }
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routeCollection->getRoutes();
    }

}
