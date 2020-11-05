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
use Router\Exceptions\BadConfigConfigurationException;
use Router\Exceptions\BadRouteConfigurationException;
use Router\Exceptions\ResourceNotFoundException;
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

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @param string $dir
     */
    public function addDir(string $dir): void
    {
        $iterator = new FilesystemIterator($dir);
        $filter = new RegexIterator($iterator, '/^.*\.(yaml)$/i');
        $files = [];
        foreach($filter as $entry){
            $files[] = $entry->getPathname();
        }
        if(!empty($files)){
            $this->addRouteFiles($files);
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
    }

    /**
     * @param RouteCollection|null $routeCollection
     */
    public function fetchRoutes(RouteCollection $routeCollection): void
    {
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
     * @param RouteCollection $routeCollection
     */
    public function setRouteCollection(RouteCollection $routeCollection): void
    {
        $this->routeCollection = $routeCollection;
    }

}
