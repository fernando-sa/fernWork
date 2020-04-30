<?php

require __DIR__ . '/vendor/autoload.php';

use fernandoSa\ORMonster\Model;
use fernandoSa\Database\Connection;
use fernandoSa\Database\Drivers\MySqlPdo;

$connection = new Connection('mysql', 'localhost', 'orm', 'user', 'secret');

$pdo = new MySqlPdo($connection->getPdo());

$model = new Model();

$model->setDriverAmbient($pdo);

// $model->name = "Fernando";
// $model->email = "teste@teste.teste";
// $model->save();

// $model->id = 1;
// $model->name = "Outro Fernando";
// $model->save();

$modelsCount = count($model->findAll());

$model->id = $modelsCount;
$model->name = "teste";

$model->delete();
