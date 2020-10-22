<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router;

use http\Exception\InvalidArgumentException;
use Router\Entity\Route;

class RouteCollection
{
    /** @var Route[] */
    private $routes = [];

    /**
     * @param Route $route
     */
    public function addRoute(Route $route) {
        $checkRouteExist = array_filter($this->routes, function($uniqueObject) use ($route) {
            return $uniqueObject->getName() == $route->getName();
        });
        if($checkRouteExist) {
            throw new \Exception("The route " . $route->getName() . " already exists!");
        }
        $this->routes[] = $route;
    }

    /**
     * @param string $name
     * @param array $routeConfig
     */
    public function addSimpleRoute(string $name, array $routeConfig) {
        if(empty($name) || empty($routeConfig)){
            throw new InvalidArgumentException('Invalid route');
        }
        $route = new Route($name, $routeConfig);
        $this->addRoute($route);
    }

    /**
     * @return Route[]
     */
    public function getRoutes() :array {
        return $this->routes;
    }

}
