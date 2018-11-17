<?php

namespace App\Tests;

use App\Application;
use App\Services\TicTacToe;

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \App\Application
     */
    protected $application;

    public function setUp()
    {
        $this->application = new Application();
    }

    public function testApplicationIsBootable()
    {
        $this->assertInstanceOf(Application::class, $this->application);
    }

    public function testTicTacToeIsInstantiated()
    {
        $this->assertInstanceOf(
            TicTacToe::class,
            $this->application->getTicTacToe()
        );
    }
}
