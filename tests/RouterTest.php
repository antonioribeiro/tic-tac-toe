<?php

namespace App\Tests;

use App\Exceptions\MethodNotAllowedException;
use App\Exceptions\MethodNotFoundException;
use App\Exceptions\NotFoundHttpException;
use App\Http\Controllers\Home;
use App\Services\Router;
use App\Services\Request;

class RouterTest extends \PHPUnit\Framework\TestCase
{
    protected $router;

    public function setUp()
    {
        $this->router = new Router();
    }

    public function testCanMatchARoute()
    {
        $matched = $this->router->match(
            new Request([], [], [], [], [], ['REQUEST_URI' => '/'])
        );

        $this->assertEquals($matched['action'], 'Home@index');

        $this->assertEquals($matched['vars'], []);
    }

    public function testCanPostToARoute()
    {
        $matched = $this->router->match(
            new Request(
                [],
                [],
                [],
                [],
                [],
                ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/play']
            )
        );

        $this->assertEquals($matched['action'], 'TicTacToe@play');
    }

    public function testThrowsExceptionOnStrangeBoardLayout()
    {
        $this->expectException(NotFoundHttpException::class);

        $matched = $this->router->match(
            new Request(
                [],
                [],
                [],
                [],
                [],
                ['REQUEST_METHOD' => 'POST', 'REQUEST_URI' => '/notFound']
            )
        );
    }

    public function testInvalidMethod()
    {
        $this->expectException(MethodNotAllowedException::class);

        $this->router->match(
            new Request([], [], [], [], [], ['REQUEST_URI' => '/play'])
        );
    }

    public function testControllerActionMethodNotFound()
    {
        $this->expectException(MethodNotFoundException::class);

        $this->router->callControllerMethod(
            Home::class,
            new Home(),
            'store',
            new Request([], [], [], [], [], ['REQUEST_URI' => '/play'])
        );
    }
}
