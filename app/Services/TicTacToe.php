<?php

namespace App\Services;

use App\Contracts\MoveInterface;
use App\Exceptions\WrongBoardSizeException;

class TicTacToe implements MoveInterface
{
    /**
     * The current board.
     *
     * @var Board
     */
    protected $board;

    /**
     * Our artificial intelligence being.
     *
     * @var Robot
     */
    protected $robot;

    /**
     * TicTacToe constructor.
     *
     * @param array $boardState
     * @param int $size
     * @throws WrongBoardSizeException
     */
    public function __construct(array $boardState = [], int $size = 3)
    {
        $this->initialize($boardState, $size);
    }

    /**
     * Get the board.
     *
     * @return Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * Initialize game.
     *
     * @param array $boardState
     * @param int $size
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    protected function initialize(array $boardState = [], int $size = 3): void
    {
        $this->board = new Board($boardState, $size);

        $this->robot = new Robot();
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
    public function makeMove($boardState, $playerUnit = 'X'): array
    {
        $this->initialize($boardState);

        $nextMove = $this->robot->makeMove(
            $this->board->getState(),
            $playerUnit
        );

        return $this->board
            ->registerMove($nextMove[0], $nextMove[1], $nextMove[2])
            ->getState();
    }
}
