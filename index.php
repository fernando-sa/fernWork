<?php

require __DIR__ . '/vendor/autoload.php';

use fernandoSa\ORMonster\Model;
use fernandoSa\Database\Connection;
use fernandoSa\Database\Drivers\MySqlPdo;
use fernandoSa\Router\Router;

$connection = new Connection('mysql', 'localhost', 'orm', 'user', 'secret');

$pdo = new MySqlPdo($connection->getPdo());

$model = new Model();

// Remove this when implement DI
$model->setDriverAmbient($pdo);

$path_info = $_SERVER['PATH_INFO'] ?? '/';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router = new Router($path_info, $request_method);

$router->get('/hello/{name}/{otherName}', function ($parameters) {
    return "<h1>{$parameters['name']} {$parameters['otherName']}</h1>";
});

$router->get('/teste/{name}', function ($parameters) {
    return "<h1 style='color:red'>{$parameters[1]}</h1>";
});

$routeResult = $router->run();
$result = call_user_func($routeResult['callback'], $routeResult['parameters']);

print_r($result);
