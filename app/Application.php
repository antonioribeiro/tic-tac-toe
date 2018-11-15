<?php

namespace App;

use App\Services\Board;

class Application
{
    protected $ticTacToe;

    /**
     * Application constructor.
     *
     * @param array $boardState
     * @param int $size
     * @throws Exceptions\WrongBoardSizeException
     */
    public function __construct(array $boardState = [], int $size = 3)
    {
        $this->initialize($boardState, $size);
    }

    /**
     * Get the tic tac toe instance.
     *
     * @return Board
     */
    public function getTicTacToe()
    {
        return $this->ticTacToe;
    }

    /**
     * Initialize Tic Tac Toe object.
     *
     * @param array $boardState
     * @param int $size
     * @throws Exceptions\WrongBoardSizeException
     */
    protected function initialize(array $boardState, int $size): void
    {
        $this->ticTacToe = new Board($boardState, $size);
    }
}
