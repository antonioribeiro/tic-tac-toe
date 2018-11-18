<?php

namespace App\Services;

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
     * The current player.
     *
     * @var string
     */
    protected $player;

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
     * Get the current opponent.
     *
     * @return string
     */
    public function getOpponent(): string
    {
        return infer_opponent($this->getPlayer());
    }

    /**
     * Get the current player.
     *
     * @return string
     */
    public function getPlayer(): string
    {
        return $this->player;
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
     * @param string|null $playerUnit
     * @return TicTacToe
     * @throws \App\Exceptions\MoveNotAvailableException
     * @throws \App\Exceptions\WrongMoveException
     */
    public function opponentMove(
        int $col,
        int $row,
        string $playerUnit = null
    ): TicTacToe {
        $this->board->registerMove(
            $col,
            $row,
            $playerUnit ?: $this->getOpponent()
        );

        return $this;
    }

    /**
     * Play an AI move.
     *
     * @param string|null $playerUnit
     * @return $this
     * @throws \App\Exceptions\MoveNotAvailableException
     * @throws \App\Exceptions\WrongMoveException
     */
    public function play(string $playerUnit = null)
    {
        $this->setPlayer($playerUnit);

        $nextMove = $this->ai->makeMove(
            $this->board->getState(),
            $this->getPlayer()
        );

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
     * Set the current opponent player.
     *
     * @param string|null $player
     * @return TicTacToe
     */
    public function setPlayer($player): TicTacToe
    {
        $this->player = $player
            ? $player
            : ($this->player
                ? $this->player
                : 'O');

        return $this;
    }
}
