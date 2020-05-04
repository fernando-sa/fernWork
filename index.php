<?php

require __DIR__ . '/vendor/autoload.php';

use fernandoSa\ORMonster\Model;
use fernandoSa\Database\Connection;
use fernandoSa\Database\Drivers\MySqlPdo;
use fernandoSa\DependencySyringe\Resolver;
use fernandoSa\Router\Router;

$connection = new Connection('mysql', 'localhost', 'orm', 'user', 'secret');

$pdo = new MySqlPdo($connection->getPdo());

$model = new Model();

// Remove this when implement DI
$model->setDriverAmbient($pdo);

$path_info = $_SERVER['PATH_INFO'] ?? '/';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router = new Router($path_info, $request_method);

$router->get('/hello/{name}/{otherName}', function ($teste) {
    return "<h1>{$teste['name']} {$teste['otherName']}</h1>";
});

$router->get('/teste/{name}', function ($parameters) {
    var_dump($parameters[0]['name']);
    return "<h1 style='color:red'>{$parameters[0]['name']}</h1>";
});

$router->post('/testePost', function ($parameters) {
    $array = implode(', ', $_POST);
    return "<h1 style='color:red'>{$array}</h1>\n";
});

$routeResult = $router->run();

$resolver = new Resolver;

$result = $resolver->resolveMethod($routeResult['callback'], ['parameters' => ['name' => "Fernando"]]);

print_r($result);
