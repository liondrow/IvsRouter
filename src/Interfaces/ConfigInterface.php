<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Interfaces;

use Router\RouteCollection;


interface ConfigInterface
{

    /**
     * @param string $dirName
     * @return void
     */
    public function addRoutesDir(string $dirName): void;

    /**
     * @param array $directories
     * @return void
     */
    public function addRoutesDirArray(array $directories): void;

    /**
     * @return RouteCollection
     */
    public function parseConfig(): RouteCollection;
}
