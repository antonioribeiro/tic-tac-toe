<?php

namespace App\Tests;

use App\Services\AI;

class AITest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \App\Services\AI
     */
    protected $ai;

    public function setUp()
    {
        $this->ai = new AI([['', '', ''], ['', '', ''], ['', '', '']], 'O');
    }

    public function testCanInstantiateGame()
    {
        $this->assertEquals($this->ai->getBoard()->getState(), [
            ['', '', ''],
            ['', '', ''],
            ['', '', ''],
        ]);
    }

    public function testCanInitializeGameWithAnyKindOfBoard()
    {
        $this->ai = new AI(
            ($state = [
                ['', '', '', ''],
                ['', 'X', 'X', ''],
                ['', 'X', 'X', ''],
                ['', '', '', ''],
            ])
        );

        $this->assertEquals($this->ai->getBoard()->getState(), $state);
    }

    public function testCanMakeMoves()
    {
        $initial = [['O', 'O', ''], ['', 'X', ''], ['', '', '']];

        $optimalMove = [['O', 'O', 'X'], ['', 'X', ''], ['', '', '']];

        $move = $this->ai->makeMove($initial, 'X');

        $withMove = $this->ai
            ->getBoard()
            ->registerMove($move[0], $move[1], $move[2])
            ->getState();

        $this->assertEquals($withMove, $optimalMove);
    }

    public function testCanResultInDraw()
    {
        $initial = [['O', 'X', 'O'], ['X', 'X', 'O'], ['O', 'O', '']];

        $this->ai->play('X', $initial);

        $this->assertEquals('D', $this->ai->getBoard()->getResultFor('X'));
    }

    public function testCanResultInWin()
    {
        $initial = [['O', 'X', 'O'], ['X', 'X', 'O'], ['O', 'O', '']];

        $this->ai->play('O', $initial);

        $this->assertEquals('W', $this->ai->getBoard()->getResultFor('O'));
    }

    public function testCanWorkWithNoMovesLeft()
    {
        $initial = [['O', 'X', 'O'], ['X', 'X', 'O'], ['O', 'O', 'X']];

        $this->ai->play('O', $initial);

        $this->assertEquals('D', $this->ai->getBoard()->getResultFor('O'));
    }
}
