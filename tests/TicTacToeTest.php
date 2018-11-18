<?php

namespace App\Tests;

use App\Services\TicTacToe;

class TicTacToeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \App\Services\TicTacToe
     */
    protected $ticTacToe;

    public function setUp()
    {
        $this->ticTacToe = new TicTacToe([
            ["X", "", ""],
            ["", "", ""],
            ["", "", ""],
        ]);
    }

    public function testTicTacToeIsBootable()
    {
        $this->assertInstanceOf(TicTacToe::class, $this->ticTacToe);
    }

    public function testCanPlayWithOpponent()
    {
        $this->assertEquals(
            '[["X","",""],["","O",""],["","",""]]',
            json_encode(
                $this->ticTacToe
                    ->opponentMove(1, 1, 'O')
                    ->getBoard()
                    ->getState()
            )
        );

        $this->assertEquals(
            '[["X","X",""],["","O",""],["","",""]]',
            json_encode(
                $this->ticTacToe
                    ->play('X')
                    ->getBoard()
                    ->getState()
            )
        );
    }
}
