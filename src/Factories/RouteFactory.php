<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Factories;


use Router\Entity\Route;

class RouteFactory
{

    public static function getRouteFromArray(string $name, array $params): Route
    {
        $routeArray = [
            "name" => $name,
            "url" => $params[0],
            "target" => $params[1],
            "methods" => $params[2]
        ];

        return new Route($routeArray);
    }

}
