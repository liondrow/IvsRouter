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

/**
 * Interface LoaderInterface
 * @package Router\Interfaces
 */
interface LoaderInterface
{

    /**
     * @param string $dir
     * @return void
     */
    public function addDir(string $dir): void;

    /**
     * @param RouteCollection $routeCollection
     * @return void
     */
    public function fetchRoutes(RouteCollection $routeCollection): void;

}
