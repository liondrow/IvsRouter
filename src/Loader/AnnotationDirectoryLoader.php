<?php
/*
 * Copyright (c) 2020.
 * This file is part of the DigiBears application.
 * (c) DigiBears - Ivan Sereda <liondrow2@yandex.ru>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Router\Loader;


use Doctrine\Common\Annotations\AnnotationReader;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RegexIterator;
use Router\Exceptions\BadConfigConfigurationException;
use Router\Route;
use Router\Exceptions\ResourceNotFoundException;
use Router\Interfaces\LoaderInterface;
use Router\RouteCollection;

class AnnotationDirectoryLoader implements LoaderInterface
{

    /** @var AnnotationReader */
    private $reader;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * AnnotationDirectoryLoader constructor.
     */
    public function __construct()
    {
        $this->reader = new AnnotationReader();
    }

    public function addDir(string $dir): void
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $regex    = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        foreach ($regex as $file => $value) {
            $current = $this->parseTokens(token_get_all(file_get_contents(str_replace('\\', '/', $file))));
            if ($current !== false) {
                list($namespace, $class) = $current;
                try {
                    $refClass = new ReflectionClass($namespace . $class);
                } catch (ReflectionException $e) {
                    echo $e->getMessage();
                }
                if ($refClass->isAbstract()) {
                    continue;
                }
                $refMethods = $refClass->getMethods(ReflectionMethod::IS_PUBLIC);
                $routes = [];
                foreach($refMethods as $method)
                {
                    $annotation = $this->reader->getMethodAnnotation($method,Route::class);
                    if($annotation) {
                        if($annotation instanceof Route){
                            $annotation->setTarget($refClass->getName() . "@" . $method->getName());
                        }
                        $routes[] = $annotation;
                    }
                }
                $this->routeCollection->addRoutesArray($routes);
            } else {
                throw new ResourceNotFoundException("Suitable for the configuration classes were not found");
            }
        }
    }

    public function fetchRoutes(RouteCollection $routeCollection): void
    {
        if(empty($routeCollection->getRoutes())){
            throw new BadConfigConfigurationException("No available routes found");
        }
    }

    private function parseTokens(array $tokens) {
        $nsStart    = false;
        $classStart = false;
        $namespace  = '';
        foreach ($tokens as $token) {
            if ($token[0] === T_CLASS) {
                $classStart = true;
            }
            if ($classStart && $token[0] === T_STRING) {
                return [$namespace, $token[1]];
            }
            if ($token[0] === T_NAMESPACE) {
                $nsStart = true;
            }
            if ($nsStart && $token[0] === ';') {
                $nsStart = false;
            }
            if ($nsStart && $token[0] === T_STRING) {
                $namespace .= $token[1] . '\\';
            }
        }

        return false;
    }

    /**
     * @param RouteCollection $routeCollection
     */
    public function setRouteCollection(RouteCollection $routeCollection): void
    {
        $this->routeCollection = $routeCollection;
    }
}
