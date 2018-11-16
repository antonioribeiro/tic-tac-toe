<?php

namespace App\Tests;

use App\Services\TicTacToe;

class AITest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \App\Services\TicTacToe
     */
    protected $ticTacToe;

    public function setUp()
    {
        $this->ticTacToe = new TicTacToe();
    }

    public function testCanInstantiateGame()
    {
        $this->assertEquals($this->ticTacToe->getBoard()->getState(), [
            ['', '', ''],
            ['', '', ''],
            ['', '', ''],
        ]);
    }

    public function testCanInitializeGameWithAnyKindOfBoard()
    {
        $this->ticTacToe = new TicTacToe(
            ($state = [
                ['', '', '', ''],
                ['', 'X', 'X', ''],
                ['', 'X', 'X', ''],
                ['', '', '', ''],
            ])
        );

        $this->assertEquals($this->ticTacToe->getBoard()->getState(), $state);
    }

    public function testCanMakeMoves()
    {
        $initial = [['O', 'O', ''], ['', 'X', ''], ['', '', '']];

        $optimalMove = [['O', 'O', 'X'], ['', 'X', ''], ['', '', '']];

        $withMove = $this->ticTacToe->makeMove($initial, 'X');

        $this->assertEquals($withMove, $optimalMove);
    }
}
