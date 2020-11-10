<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Cache;


class RouterFileCache
{

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var int
     */
    private $lifeTime;

    public function __construct(string $cacheDir = null, string $filename = 'routes', int $lifeTime = 86400)
    {
        $this->cacheDir = $cacheDir ?? dirname($_SERVER['DOCUMENT_ROOT']) . '/cache/router';
        $this->filename = $filename;
        $this->lifeTime = $lifeTime;
    }

    /**
     * @return string
     */
    private function getCacheDir() :string
    {
        if(!is_dir($this->cacheDir)){
            if(!mkdir($this->cacheDir, 0755, true)) {
                $mkdirError = error_get_last();
                throw new \Exception('Cant create directory ' .$mkdirError['message'], 1);
            }
        }
        return $this->cacheDir;
    }

    public function save($data) {
        $cacheFileName = $this->getCacheDir() . $this->filename;
        $cacheLifeTime = time() + $this->lifeTime;
        $dataArr = [
            'lifeTime' => $cacheLifeTime,
            'data' => $data
        ];
        $cacheData = serialize($dataArr);
        return file_put_contents($cacheFileName, $cacheData);
    }

    public function append($data) {
        $cacheData = $this->get();
        if(!empty($cacheData['data'])){
            $key = array_key_first($data);
            $cacheData['data'][$key] = $data[$key];
        }
        $this->save($cacheData['data']);
    }

    public function get() {
        $cacheFileName = $this->getCacheDir() . $this->filename;
        if(is_file($cacheFileName)) {
            $cacheData = unserialize(file_get_contents($cacheFileName));
            if($cacheData['lifeTime'] > time()){
                return $cacheData;
            }
            $this->clearCache();
        }
        return false;
    }

    public function clearCache() {
        @unlink($this->getCacheDir() . $this->filename);
    }


}
