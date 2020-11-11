<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Interfaces;


interface Cache
{

    /**
     * @param array $data
     */
    public function save(array $data);

    /**
     * @return mixed
     */
    public function get();

    /**
     * @param array $data
     * @return mixed
     */
    public function append(array $data);

    /**
     * @return void
     */
    public function clearCache(): void;
}
