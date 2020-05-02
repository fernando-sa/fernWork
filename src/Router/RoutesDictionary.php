<?php

namespace fernandoSa\Router;

class RoutesDictionary
{
    private $routes = [];

    public function add(string $method, string $path, callable $callback) : void
    {
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }
        $currentRoutesIndex = count($this->routes[$method]);

        $this->routes[$method][$currentRoutesIndex]['path'] = $path;
        $this->routes[$method][$currentRoutesIndex]['callback'] = $callback;
    }

    public function findByMethod(string $method) : array
    {
        if (! array_key_exists($method, $this->routes)) {
            return [];
        }
        
        return $this->routes[$method];
    }


    public function findAll() : array
    {
        return $this->routes;
    }
}
