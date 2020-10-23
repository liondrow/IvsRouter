<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Factories;


use Router\Config\AnnotationConfig;
use Router\Config\YamlConfig;
use Router\Exceptions\BadConfigConfigurationException;

class ConfigFactory
{

    public static function YamlConfig()
    {
        return new YamlConfig();
    }

    public static function AnnotationConfig(string $controllersDir)
    {
        if(empty($controllersDir)){
            throw new BadConfigConfigurationException('The directory with controllers is not specified!');
        }
        return new AnnotationConfig($controllersDir);
    }




}
