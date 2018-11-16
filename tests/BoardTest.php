<?php

namespace App\Tests;

use App\Services\Board;
use App\Exceptions\WrongMoveException;
use App\Exceptions\WrongBoardSizeException;
use App\Exceptions\MoveNotAvailableException;

class BoardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \App\Services\Board
     */
    protected $board;

    public function setUp()
    {
        $this->board = new Board();
    }

    public function testCanInstantiate()
    {
        $this->assertTrue(is_array($this->board->getState()));

        $this->assertEquals($this->board->getState(), [
            ['', '', ''],
            ['', '', ''],
            ['', '', ''],
        ]);
    }

    public function testInitialBoardSizeIsCorrect()
    {
        $this->assertCount(3, $this->board->getState());

        $this->assertCount(3, $this->board->getState()[0]);
    }

    public function testCanCreateBiggerBoardSizes()
    {
        $this->board = new Board([], ($size = 10));

        $this->assertCount($size, $this->board->getState());

        $this->assertCount($size, $this->board->getState()[9]);
    }

    public function testCanCreateCrazyBoardWithState()
    {
        $this->board = new Board([['', '', ''], ['', 'X', ''], ['', '', '']]);

        $this->assertEquals(
            [['', '', ''], ['', 'X', ''], ['', '', '']],
            $this->board->getState()
        );
    }

    public function testThrowsExceptionOnStrangeBoardLayout()
    {
        $this->expectException(WrongBoardSizeException::class);

        $this->board = new Board([['', '', ''], ['', 'X', ''], ['', '']]);
    }

    public function testThrowsExceptionOnTooSmallBoards()
    {
        $this->expectException(WrongBoardSizeException::class);

        $this->board = new Board([['', 'O', ''], ['', 'X', '']]);
    }

    public function testCanRegisterMove()
    {
        $this->board->registerMove(2, 0, 'X');

        $this->assertEquals(
            [['', '', 'X'], ['', '', ''], ['', '', '']],
            $this->board->getState()
        );

        $this->board->registerMove(1, 1, 'O');

        $this->assertEquals(
            [['', '', 'X'], ['', 'O', ''], ['', '', '']],
            $this->board->getState()
        );
    }

    public function testThrowsExceptionOnWrongMove()
    {
        $this->expectException(WrongMoveException::class);

        $this->board->registerMove(5, 0, 'X');
    }

    public function testThrowsExceptionOnNotAvailableMove()
    {
        $this->expectException(MoveNotAvailableException::class);

        $this->board->registerMove(1, 1, 'X');

        $this->board->registerMove(1, 1, 'O');
    }

    public function testCanFlattenABoard()
    {
        $this->assertEquals(
            [0, 1, 2, 3, 4, 5, 6, 7, 8],
            $this->board->flatten()
        );

        $board = new Board([['', 'O', ''], ['', 'X', ''], ['', '', 'X']]);

        $this->assertEquals(
            [0, 'O', 2, 3, 'X', 5, 6, 7, 'X'],
            $board->flatten()
        );
    }

    public function testCanWinnRow()
    {
        $board = new Board([['X', 'X', 'X'], ['', 'X', ''], ['', '', 'X']]);
        $this->assertTrue($board->isWinner('X'));
        $this->assertFalse($board->isWinner('O'));

        $board = new Board([['O', 'O', ''], ['X', 'X', 'X'], ['', '', '']]);
        $this->assertTrue($board->isWinner('X'));
        $this->assertFalse($board->isWinner('O'));

        $board = new Board([['', '', ''], ['', '', ''], ['X', 'X', 'X']]);
        $this->assertTrue($board->isWinner('X'));
        $this->assertFalse($board->isWinner('O'));

        $board = new Board([
            ['', '', '', ''],
            ['O', 'O', 'O', 'O'],
            ['', 'X', 'X', ''],
            ['', 'X', 'X', ''],
        ]);
        $this->assertTrue($board->isWinner('O'));
        $this->assertFalse($board->isWinner('X'));
    }

    public function testCanWinnColumn()
    {
        $board = new Board([['X', '', ''], ['X', '', 'O'], ['X', '', 'O']]);
        $this->assertTrue($board->isWinner('X'));
        $this->assertFalse($board->isWinner('O'));

        $board = new Board([['', 'X', ''], ['', 'X', ''], ['', 'X', '']]);
        $this->assertTrue($board->isWinner('X'));
        $this->assertFalse($board->isWinner('O'));

        $board = new Board([['', '', 'X'], ['', '', 'X'], ['', '', 'X']]);
        $this->assertTrue($board->isWinner('X'));
        $this->assertFalse($board->isWinner('O'));

        $board = new Board([
            [' ', 'O', ' ', ' '],
            [' ', 'O', 'O', 'O'],
            [' ', 'O', 'X', ' '],
            [' ', 'O', 'X', ' '],
        ]);
        $this->assertTrue($board->isWinner('O'));
        $this->assertFalse($board->isWinner('X'));
    }

    public function testCanWinnDiagonal()
    {
        $board = new Board([['X', '', ''], ['', 'X', 'O'], ['', '', 'X']]);
        $this->assertTrue($board->isWinner('X'));
        $this->assertFalse($board->isWinner('O'));

        $board = new Board([['', '', 'X'], ['', 'X', ''], ['X', '', '']]);
        $this->assertTrue($board->isWinner('X'));
        $this->assertFalse($board->isWinner('O'));

        $board = new Board([
            ['O', ' ', ' ', ' '],
            [' ', 'O', ' ', ' '],
            [' ', ' ', 'O', ' '],
            [' ', ' ', ' ', 'O'],
        ]);
        $this->assertTrue($board->isWinner('O'));
        $this->assertFalse($board->isWinner('X'));
    }

    public function testCanGetAvailableMoves()
    {
        $board = new Board([['X', 'X', 'X'], ['', 'X', ''], ['', '', 'X']]);

        $this->assertEquals([3, 5, 6, 7], $board->getAvailableMoves());
    }

    public function testConvertMoveTo2D()
    {
        $this->assertEquals(
            $this->board->getState(),
            $this->board->unFlatten($this->board->flatten())
        );
    }
}
