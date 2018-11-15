<?php

namespace App;

use App\Services\TicTacToe;

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
     * Redirect calls to unavailable methods to the Tic Tac Toe object.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->ticTacToe, $name], $arguments);
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
        $this->ticTacToe = new TicTacToe($boardState, $size);
    }
}
