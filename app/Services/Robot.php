<?php

namespace App\Services;

use App\Contracts\MoveInterface;

class Robot implements MoveInterface
{
    /**
     * @var Board
     */
    protected $board;

    /**
     * @var string
     */
    protected $us;

    /**
     * @var string
     */
    protected $them;

    /**
     * Robot constructor.
     *
     * @param Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    /**
     * Infer the opponent.
     *
     * @param string $us
     */
    protected function inferOpponent(string $us)
    {
        $this->us = $us;

        $this->them = $us === 'X' ? 'O' : 'X';
    }

    /**
     * Makes a move using the $boardState
     * $boardState contains 2 dimensional array of the game field
     * X represents one team, O - the other team, empty  string means field is not yet taken.
     * example
     * [
     *   ['X', 'O', '']
     *   ['X', 'O', 'O']
     *   ['', '', '']
     * ]
     * Returns an array, containing x and y coordinates for next move, and the unit that now occupies it.
     * Example: [2, 0, 'O'] - upper right corner - O player
     *
     * @param array $boardState Current board state
     * @param string $playerUnit Player unit representation
     *
     * @return array
     */
    public function makeMove($boardState, $playerUnit = 'X')
    {
        $this->inferOpponent($playerUnit);

        return [0, 0, $playerUnit];
    }
}
