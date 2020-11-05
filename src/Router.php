<?php
declare(strict_types=1);

namespace Router;

use Router\Exceptions\BadConfigConfigurationException;

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


    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes->getRoutes();
        $this->requestUri = $this->getRequestUri();
        $this->requestMethod = $this->getRequestMethod();
    }

    /**
     * @return string
     */
    public function getRequestUri()
    {
        $uri = rawurldecode($_SERVER['REQUEST_URI']);
        return ($uri == '/') ? '/' : rtrim(parse_url($uri, PHP_URL_PATH), '/');
    }

    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function matchRequest()
    {
        if($route = $this->match()){
           $this->invoke($route);
        }
    }

    public function match()
    {
        foreach ($this->routes as $route) {

            if (preg_match_all('#^' . $route->getUrl() . '$#', $this->requestUri, $matches)) {
                $this->params = [];
                array_shift($matches);
                if(!empty($matches) && is_array($matches)){
                    foreach ($matches as $match) {
                        $this->params[] = $match[0];
                    }
                }
            }

            if($this->checkAvailablePattern($route->getUrl()) && $this->checkAvailableMethod($route->getMethods())) {
                return $route;
            }
        }
        return false;
    }

    public function invoke(Route $route)
    {
        $target = explode('@', $route->getTarget());
        if($this->checkAvailableRouteTarget($target)) {
            $controller = new $target[0];
            $action = $target[1];
            try {
                call_user_func_array([$controller, $action], $this->params);
            } catch (\ArgumentCountError $countError) {
                echo "Пошёл нахуй";
            }

        }
    }

    private function checkAvailableRouteTarget(array $routeTarget)
    {
        if(!class_exists($routeTarget[0])){
            throw new BadConfigConfigurationException("Class " . $routeTarget[0] . " not exist!");
        }
        if(!method_exists($routeTarget[0], $routeTarget[1])){
            throw new BadConfigConfigurationException("Method " . $routeTarget[1] . " in Class " . $routeTarget[0] . " not exist!");
        }
        return true;
    }

    private function checkAvailablePattern(string $routeUrl)
    {
        $pattern = '@^' . $routeUrl . '/?$@i';
        return (preg_match($pattern, $this->requestUri));
    }

    private function checkAvailableMethod(array $methods)
    {
        return in_array($this->requestMethod, $methods, true);
    }




}
