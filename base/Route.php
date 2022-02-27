<?php
namespace Base;

class Route
{
    private $routes;
    private $controller;
    private $action;

    public function add(string $route, $controllerName, $actionName = 'index')
    {
        $this->routes[$route] = [$controllerName, $actionName];
    }

    public function auto($uri)
    {
        $parts = explode('/', $uri);
        if (empty($parts[1])) {
            return false;
        }
        $controllerName = $parts[1];
        $actionName = 'index';
        if (isset($parts[2])) {
            $actionName = $parts[2];
        }
        $controllerClassName = 'App\\Controller\\' . ucfirst(strtolower($controllerName));
        if (!class_exists($controllerClassName)) {
            return false;
        }

        $this->controller = new $controllerClassName();
        if (!method_exists($this->controller, $actionName)) {
            return false;
        }

        $this->action = $actionName;
        return true;
    }

    /**
     * @param string $uri
     * @throws Error404Exception
     */
    public function dispatch(string $uri)
    {
        $parsed = parse_url($uri);
        $uri = $parsed['path'];
        if (isset($this->routes[$uri])) {
            $this->controller = new $this->routes[$uri][0];
            $this->action = $this->routes[$uri][1];
            return;
        }

        if (!$this->auto($uri)) {
            throw new Error404Exception();
        }
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }
}