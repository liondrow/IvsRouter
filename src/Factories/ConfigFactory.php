<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Factories;


use Router\Config\YamlConfig;

class ConfigFactory
{

    public static function YamlConfig()
    {
        return new YamlConfig();
    }






}
