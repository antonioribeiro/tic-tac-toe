<?php

namespace App\Services;

use App\Contracts\RegisterMoveInterface;
use App\Exceptions\WrongBoardSizeException;

class TicTacToe
{
    /**
     * The current board.
     *
     * @var Board
     */
    protected $board;

    /**
     * The current opponent player.
     *
     * @var Board
     */
    protected $opponent;

    /**
     * Our artificial intelligence being.
     *
     * @var AI
     */
    protected $ai;

    /**
     * TicTacToe constructor.
     *
     * @param array|string $boardState
     * @param int $size
     * @throws WrongBoardSizeException
     */
    public function __construct($boardState = [], int $size = 3)
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
     * @param array|string $boardState
     * @param int $size
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    protected function initialize($boardState = [], int $size = 3): void
    {
        $this->board = new Board($boardState, $size);

        $this->ai = new AI();
    }

    /**
     * Check if an AI move returned a winner or draw.
     *
     * @param array $move
     * @return bool
     */
    protected function isValidMove(array $move)
    {
        return $move[0] !== null;
    }

    /**
     * Register a move in the current board.
     *
     * @param int $col
     * @param int $row
     * @param string $playerUnit
     * @return TicTacToe
     * @throws \App\Exceptions\MoveNotAvailableException
     * @throws \App\Exceptions\WrongMoveException
     */
    public function opponentMove(
        int $col,
        int $row,
        string $playerUnit
    ): TicTacToe {
        $this->board->registerMove($col, $row, $playerUnit);

        return $this;
    }

    /**
     * Play an AI move.
     *
     * @param string $playerUnit
     * @return $this
     * @throws \App\Exceptions\MoveNotAvailableException
     * @throws \App\Exceptions\WrongMoveException
     */
    public function play(string $playerUnit)
    {
        $nextMove = $this->ai->makeMove($this->board->getState(), $playerUnit);

        if ($this->isValidMove($nextMove)) {
            $this->board->registerMove(
                $nextMove[0],
                $nextMove[1],
                $nextMove[2]
            );
        }

        return $this;
    }

    /**
     * Get the current opponent.
     *
     * @return string
     */
    public function getOpponent(): string
    {
        return $this->opponent;
    }

    /**
     * Get the current player.
     *
     * @return string
     */
    public function getPlayer(): string
    {
        return $this->getOpponent() === 'O' ? 'X' : 'O';
    }

    /**
     * Register an opponent move and make AI play a move.
     *
     * @return array
     * @throws \App\Exceptions\MoveNotAvailableException
     * @throws \App\Exceptions\WrongMoveException
     */
    protected function registerAndPlay(): array
    {
        return $this->opponentMove(
            $this->getParam('column'),
            $this->getParam('row'),
            $this->getOpponent()
        )
            ->play($this->getPlayer())
            ->getBoard()
            ->getState();
    }

    /**
     * Set the current opponent player.
     *
     * @param string $player
     * @return TicTacToe
     */
    public function setOpponent($player): TicTacToe
    {
        $this->opponent = $player;

        return $this;
    }
}
