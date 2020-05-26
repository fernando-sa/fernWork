<?php

namespace fernandoSa;

use fernandoSa\Database\Connection;
use fernandoSa\DependencySyringe\Resolver;
use fernandoSa\Router\Router;

class App
{
    private $connection;
    public $router;

    public function connectDB($mysql, $host, $databaseName, $user, $password) : object
    {
        $this->connection = new Connection($mysql, $host, $databaseName, $user, $password);
        return $this->connection;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }

    public function setRouter() : void
    {
        $path_info = $_SERVER['PATH_INFO'] ?? '/';
        $request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $this->router = new Router($path_info, $request_method);
        $this->router->parseRoutesFiles();
    }

    public function run() : void
    {
        $routeResult = $this->router->run();

        $resolver = new Resolver;

        $result = $resolver->resolveMethod($routeResult['callback'], $routeResult['parameters']);

        print_r($result);
    }
}
