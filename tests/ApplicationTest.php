<?php

namespace App\Tests;

use App\Application;
use App\Exceptions\MoveNotAvailableException;
use App\Exceptions\WrongBoardSizeException;
use App\Exceptions\WrongMoveException;

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \App\Application
     */
    protected $ticTacToe;

    public function setUp()
    {
        $this->ticTacToe = new Application();
    }

    public function testApplicationIsBootable()
    {
        $this->assertTrue(is_array($this->ticTacToe->getBoardState()));

        $this->assertEquals($this->ticTacToe->getBoardState(), [
            ['', '', ''],
            ['', '', ''],
            ['', '', ''],
        ]);
    }

    public function testInitialBoardSizeIsCorrect()
    {
        $this->assertCount(3, $this->ticTacToe->getBoardState());

        $this->assertCount(3, $this->ticTacToe->getBoardState()[0]);
    }

    public function testCanCreateBiggerBoardSizes()
    {
        $this->ticTacToe = new Application([], ($size = 10));

        $this->assertCount($size, $this->ticTacToe->getBoardState());

        $this->assertCount($size, $this->ticTacToe->getBoardState()[9]);
    }

    public function testCanCreateCrazyBoardWithState()
    {
        $this->ticTacToe = new Application([
            ['', '', ''],
            ['', 'X', ''],
            ['', '', ''],
        ]);

        $this->assertEquals(
            [['', '', ''], ['', 'X', ''], ['', '', '']],
            $this->ticTacToe->getBoardState()
        );
    }

    public function testThrowsExceptionOnWrongBoardSizes()
    {
        $this->expectException(WrongBoardSizeException::class);

        $this->ticTacToe = new Application([
            ['', '', ''],
            ['', 'X', ''],
            ['', ''],
        ]);
    }

    public function testCanRegisterMove()
    {
        $this->ticTacToe->registerMove(2, 0, 'X');

        $this->assertEquals(
            [['', '', 'X'], ['', '', ''], ['', '', '']],
            $this->ticTacToe->getBoardState()
        );

        $this->ticTacToe->registerMove(1, 1, 'O');

        $this->assertEquals(
            [['', '', 'X'], ['', 'O', ''], ['', '', '']],
            $this->ticTacToe->getBoardState()
        );
    }

    public function testThrowsExceptionOnWrongMove()
    {
        $this->expectException(WrongMoveException::class);

        $this->ticTacToe->registerMove(5, 0, 'X');
    }

    public function testThrowsExceptionOnNotAvailableMove()
    {
        $this->expectException(MoveNotAvailableException::class);

        $this->ticTacToe->registerMove(1, 1, 'X');

        $this->ticTacToe->registerMove(1, 1, 'O');
    }
}
