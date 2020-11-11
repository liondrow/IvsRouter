<?php
declare(strict_types=1);

namespace IvsRouter;

use IvsRouter\Exceptions\BadConfigConfigurationException;
use IvsRouter\Interfaces\LoaderInterface;

/**
 * Class IvsRouter
 *
 * @package IvsRouter
 */
class Router
{
    /**
     * @var RouteCollection 
     */
    private $routes;

    /**
     * @var string  
     */
    private $requestUri;

    /**
     * @var string 
     */
    private $requestMethod;

    /**
     * @var array 
     */
    private $params = [];


    /**
     * IvsRouter constructor.
     *
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->routes = $loader->fetchRoutes()->getRoutes();
        $this->requestUri = $this->getRequestUri();
        $this->requestMethod = $this->getRequestMethod();
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        $uri = rawurldecode($_SERVER['REQUEST_URI']);
        return ($uri == '/') ? '/' : rtrim(parse_url($uri, PHP_URL_PATH), '/');
    }

    /**
     * @return mixed
     */
    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function matchRequest(): void
    {
        if($route = $this->match()) {
            $this->invoke($route);
        }
    }

    /**
     * @return Route
     * @throws \Exception
     */
    public function match(): Route
    {
        foreach ($this->routes as $route) {
            if (preg_match_all('#^' . $route->getUrl() . '$#', $this->requestUri, $matches)) {
                $this->params = [];
                array_shift($matches);
                if(!empty($matches) && is_array($matches)) {
                    foreach ($matches as $match) {
                        $this->params[] = $match[0];
                    }
                }
            }
            if($this->checkAvailablePattern($route->getUrl()) && $this->checkAvailableMethod($route->getMethods())) {
                return $route;
            }
        }
        throw new \Exception("throw 404");
    }

    /**
     * @param Route $route
     */
    public function invoke(Route $route)
    {
        $target = explode('@', $route->getTarget());
        if($this->checkAvailableRouteTarget($target)) {
            $controller = new $target[0];
            $action = $target[1];
            try {
                call_user_func_array([$controller, $action], $this->params);
            } catch (\ArgumentCountError $countError) {
                throw new \ArgumentCountError($countError->getMessage());
            }

        }
    }

    /**
     * @param  array $routeTarget
     * @return bool
     */
    private function checkAvailableRouteTarget(array $routeTarget): bool
    {
        if(!class_exists($routeTarget[0])) {
            throw new BadConfigConfigurationException("Class " . $routeTarget[0] . " not exist!");
        }
        if(!method_exists($routeTarget[0], $routeTarget[1])) {
            throw new BadConfigConfigurationException("Method " . $routeTarget[1] . " in Class " . $routeTarget[0] . " not exist!");
        }
        return true;
    }

    /**
     * @param  string $routeUrl
     * @return false|int
     */
    private function checkAvailablePattern(string $routeUrl)
    {
        $pattern = '@^' . $routeUrl . '/?$@i';
        return (preg_match($pattern, $this->requestUri));
    }

    /**
     * @param  array $methods
     * @return bool
     */
    private function checkAvailableMethod(array $methods): bool
    {
        return in_array($this->requestMethod, $methods, true);
    }




}
