<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Config;


use Router\Exceptions\ResourceNotFoundException;
use Router\Interfaces\ConfigInterface;
use Router\Interfaces\LoaderInterface;
use Router\Loader\AnnotationDirectoryLoader;
use Router\RouteCollection;

class AnnotationConfig implements ConfigInterface
{

    /** @var LoaderInterface */
    private $loader;

    /** @var RouteCollection */
    private RouteCollection $routeCollection;

    /**
     * YamlConfig constructor.
     * @param RouteCollection|null $routeCollection
     */
    public function __construct(RouteCollection $routeCollection = null)
    {
        $this->routeCollection = $routeCollection ?? new RouteCollection();
        $this->loader = new AnnotationDirectoryLoader($this->routeCollection);
    }

    public function addRoutesDir(string $dirName): void
    {
        if(is_dir($dirName)){
            $this->loader->addDir($dirName);
        } else {
            throw new ResourceNotFoundException("Directory $dirName does not exist!");
        }
    }

    public function addRoutesDirArray(array $directories): void
    {
        // TODO: Implement addRoutesDirArray() method.
    }

    public function parseConfig(): RouteCollection
    {
        // TODO: Implement parseConfig() method.
    }
}
