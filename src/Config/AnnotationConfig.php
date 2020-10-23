<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Config;


use Router\Loader\AnnotationDirectoryLoader;

class AnnotationConfig implements ConfigInterface
{

    /** @var string */
    public string $controllersDir;

    public function __construct(string $controllersDir)
    {
        $this->controllersDir = $controllersDir;
    }

    public function parseConfig()
    {
        $loader = new AnnotationDirectoryLoader();
        $routes = $loader->loadDirClasses($this->controllersDir);
    }

}
