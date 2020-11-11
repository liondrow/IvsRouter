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
 * Interface ConfigInterface
 * @package Router\Interfaces
 */
interface ConfigInterface
{

    /**
     * @param bool $mode
     * @return void
     */
    public function setEnvMode(bool $mode): void;

    /**
     * @param Cache $cache
     */
    public function enableCache(Cache $cache): void;

    /**
     * @return bool
     */
    public function isCacheEnabled(): bool;
}
