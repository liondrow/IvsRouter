<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Factories;

use Router\Config\Config;
use Router\Interfaces\LoaderInterface;

class LoaderFactory
{

    /**
     * @param LoaderInterface $loader
     */
    public static function getLoader(LoaderInterface $loader, Config $config)
    {
        $loader->setConfig($config);
        return $loader;
    }
}
