<?php

namespace App\Http\Controllers;

use App\Services\View;

class Home
{
    public function index()
    {
        return (new View())->make('home');
    }
}
