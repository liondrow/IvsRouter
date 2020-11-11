<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IvsRouter;

use IvsRouter\Route;
use IvsRouter\Exceptions\BadRouteConfigurationException;
use IvsRouter\Factories\RouteFactory;

class RouteCollection
{
    /** @var Route[] */
    private $routes = [];

    /**
     * @param Route $route
     */
    private function addRoute(Route $route) {
        $checkRouteExist = array_filter($this->routes, function($uniqueObject) use ($route) {
            return $uniqueObject->getName() == $route->getName();
        });
        if($checkRouteExist) {
            throw new BadRouteConfigurationException("The route " . $route->getName() . " already exists!");
        }
        $this->routes[] = $route;
    }

    /**
     * @param string $name
     * @param array $routeConfig
     */
    public function addSimpleRoute(string $name, array $routeConfig): void
    {
        if(empty($name) || empty($routeConfig)){
            throw new BadRouteConfigurationException('Invalid route');
        }
        $route = RouteFactory::getRouteFromArray($name, $routeConfig);
        $this->addRoute($route);
    }

    /**
     * @param array $routes
     */
    public function addRoutesFromArray(array $routes): void
    {
        if(empty($routes) || !is_array($routes)){
            throw new BadRouteConfigurationException('Invalid route');
        }
        foreach($routes as $routeName => $routeConfig){
            $route = RouteFactory::getRouteFromArray($routeName, $routeConfig);
            $this->addRoute($route);
        }
    }

    /**
     * @param array $routes
     */
    public function addRoutesArray(array $routes): void
    {
        if(empty($routes) || !is_array($routes)){
            throw new BadRouteConfigurationException('Invalid route');
        }
        foreach($routes as $routeObj){
            $this->addRoute($routeObj);
        }
    }

    /**
     * @return Route[]
     */
    public function getRoutes() :array {
        return $this->routes;
    }

}
