<?php

require __DIR__ . '/vendor/autoload.php';

use fernandoSa\ORMonster\Model;

$app = fernandoSa\Singleton::instance(fernandoSa\App::class);

$app->connectDB('mysql', 'localhost', 'orm', 'user', 'secret');

$app->setRouter();

$app->run();