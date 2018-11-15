<?php

namespace App\Tests;

use App\Application;
use App\Services\Board;

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
            Board::class,
            $this->application->getTicTacToe()
        );
    }
}
