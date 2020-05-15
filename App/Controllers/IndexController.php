<?php

namespace App\Controllers;

class IndexController
{
    public function index($name)
    {
        return "<h1 style='color:red'>{$name}</h1>";
    }

    public function index2($name, $otherName)
    {
        return "<h1>{$name} {$otherName}</h1>";
    }
}
