<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Config;

use FilesystemIterator;
use http\Exception;
use InvalidArgumentException;
use RegexIterator;
use Router\Entity\Route;
use Router\RouteCollection;

class YamlConfig implements ConfigInterface
{

    /** @var array */
    private array $configFiles = [];

    /** @var RouteCollection */
    private RouteCollection $routeCollection;

    /**
     * YamlConfig constructor.
     */
    public function __construct()
    {
        $this->routeCollection = new RouteCollection();
    }

    /**
     * @param array $configFiles
     */
    public function addConfigFiles(array $configFiles)
    {
        if(empty($configFiles)){
            throw new InvalidArgumentException('No configuration files specified!');
        }
        $this->configFiles = array_merge($this->configFiles, $configFiles);
    }

    /**
     * @param string $configDir
     */
    public function addConfigDir(string $configDir) {
        if(is_dir($configDir)){
            $iterator = new FilesystemIterator($configDir);
            $filter = new RegexIterator($iterator, '/^.*\.(yaml)$/i');
            $configList = [];
            foreach($filter as $entry){
                $configList[] = $entry->getPathname();
            }
            if(!empty($configList)){
                $this->addConfigFiles($configList);
            }
        } else {
            throw new \Exception("Directory $configDir does not exist!");
        }
    }

    /**
     * @return RouteCollection
     */
    public function parseConfig()
    {
        foreach($this->configFiles as $configFile){
            if(!is_file($configFile)){
                throw new InvalidArgumentException("File $configFile does not exist!");
            }
            $this->parseYamlFile($configFile);
        }
        return $this->routeCollection;
    }

    /**
     * @param string $filename
     */
    private function parseYamlFile(string $filename)
    {
        $fileRoutes = yaml_parse_file($filename);
        foreach($fileRoutes['routes'] as $routeName => $fileRoute){
            if(isset($fileRoutes['prefix'])) {
                $fileRoute[0] = $fileRoutes['prefix'] . $fileRoute[0];
            }
            $this->routeCollection->addRoute(new Route($routeName, $fileRoute));
        }
    }

}
