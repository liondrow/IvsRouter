<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IvsRouter\Config;

use IvsRouter\Interfaces\Cache;
use IvsRouter\Interfaces\ConfigInterface;
use IvsRouter\RouteCollection;

/**
 * Class Config
 * @package IvsRouter\Config
 */
class Config implements ConfigInterface
{

    const DEBUG = 0;
    const PRODUCTION = 1;

    /** @var RouteCollection */
    private RouteCollection $routeCollection;

    /** @var bool */
    private $envMode;

    /** @var Cache */
    private $cache;

    /** @var bool */
    private $cacheStatus = false;

    /**
     * Config constructor.
     * @param RouteCollection|null $routeCollection
     */
    public function __construct(RouteCollection $routeCollection = null)
    {
        $this->routeCollection = $routeCollection ?? new RouteCollection();
    }

    /**
     * @param Cache $cache
     */
    public function enableCache(Cache $cache): void
    {
        $this->cache = $cache;
        $this->cacheStatus = true;
    }

    /**
     * @return bool
     */
    public function getEnvMode(): bool
    {
        return $this->envMode;
    }

    /**
     * @param bool $envMode
     */
    public function setEnvMode(bool $envMode): void
    {
        $this->envMode = $envMode;
    }

    /**
     * @return bool
     */
    public function isCacheEnabled(): bool
    {
        return $this->cacheStatus;
    }

    /**
     * @return Cache
     */
    public function getCache(): Cache
    {
        return $this->cache;
    }
}
