<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router;

use Router\Entity\Route;

class RouteCollection
{
    /** @var Route[] */
    private $routes = [];

    /**
     * @param Route $route
     */
    public function addRoute(Route $route) {
        $this->routes[] = $route;
    }

    /**
     * @return Route[]
     */
    public function getRoutes() :array {
        return $this->routes;
    }

}
