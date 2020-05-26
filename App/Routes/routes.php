<?php

$this->get('/getUserName/{name}/{otherName}', 'IndexController@index2');
            
$this->get('/teste/{id}', 'IndexController@index');

$this->post('/testePost', function ($parameters) {
    $array = implode(', ', $_POST);
    return "<h1 style='color:red'>{$array}</h1>\n";
});


?>