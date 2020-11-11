<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IvsRouter\Interfaces;

use IvsRouter\Config\Config;
use IvsRouter\RouteCollection;

/**
 * Interface LoaderInterface
 * @package IvsRouter\Interfaces
 */
interface LoaderInterface
{

    public function setConfig(Config $config): void;

    /**
     * @param string $dir
     * @return void
     */
    public function addDir(string $dir): void;

    /**
     * @return RouteCollection
     */
    public function fetchRoutes(): RouteCollection;

    /**
     * @return array
     */
    public function getRoutes(): array;
}
