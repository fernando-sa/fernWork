<?php

require __DIR__ . '/vendor/autoload.php';

use fernandoSa\ORMonster\Model;

$app = new fernandoSa\App;

$connection = $app->connectDB('mysql', 'localhost', 'orm', 'user', 'secret');

$pdo = $connection->getSqlPdo();

$model = new Model();

// Remove this when implement DI
$model->setDriverAmbient($pdo);

$app->setRouter();

$app->get('/hello/{name}/{otherName}', 'IndexController@index2');

$app->get('/teste/{name}', 'IndexController@index');

$app->post('/testePost', function ($parameters) {
    $array = implode(', ', $_POST);
    return "<h1 style='color:red'>{$array}</h1>\n";
});

$app->run();