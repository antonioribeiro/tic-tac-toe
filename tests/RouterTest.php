<?php

namespace App\Tests;

use App\Services\Router;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $this->router = new Router();
    }

    public function testCanMatchARoute()
    {
        $matched = $this->router->match(
            new Request([], [], [], [], [], ['REQUEST_URI' => '/play'])
        );

        $this->assertEquals($matched['action'], 'TicTacToe@play');

        $this->assertEquals($matched['vars'], []);
    }
}
