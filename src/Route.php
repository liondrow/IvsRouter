<?php
declare(strict_types=1);
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IvsRouter;

/**
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
class Route
{

    /** @var string */
    private $name;

    /** @var string */
    private $url;

    /** @var string */
    private $target;

    /** @var array */
    private $methods;

    public function __construct(array $params)
    {
        $this->name = $params['name'];
        $this->url = $params['url'];
        $this->target = $params['target'] ?? '';
        $this->methods = $params['methods'];
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->getName(),
            'url' => $this->getUrl(),
            'target' => $this->getTarget(),
            'methods' => $this->getMethods(),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
    }

}
