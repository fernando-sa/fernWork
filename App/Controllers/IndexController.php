<?php

namespace App\Controllers;

use App\Models\User;

class IndexController
{
    public function index($id)
    {
        $users = (new User)->find(['id' => $id]);
        return "<h1 style='color:red'>{$users->name}</h1>";
    }

    public function index2($name, $otherName)
    {
        return "<h1>{$name} {$otherName}</h1>";
    }
}
