<?php

namespace App\Services;

use App\Contracts\MoveInterface;
use App\Services\Traits\AvailableMoves;

class AI implements MoveInterface
{
    use AvailableMoves;

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
     * AI constructor.
     *
     * @param array|string $boardState
     * @param string $playerUnit
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    public function __construct($boardState = [], string $playerUnit = 'O')
    {
        $this->initialize($boardState, $playerUnit);
    }

    /**
     * Calculate next winning moves.
     *
     * @param array $state
     * @param string $playerUnit
     * @return array
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    protected function calculateWinningMoves(
        array $state,
        string $playerUnit
    ): array {
        $spots = $this->filterAvailableMoves($state);

        $spotCount = count($spots);

        $moves = [];

        for ($i = 0; $i < $spotCount; $i++) {
            $move = ['index' => $state[$spots[$i]]];

            $state[$spots[$i]] = $playerUnit;

            $result = $this->minimax(
                $state,
                $playerUnit === $this->us ? $this->them : $this->us
            );

            $move['score'] = $result['score'];

            $state[$spots[$i]] = $move['index'];

            $moves[] = $move;
        }

        return $moves;
    }

    /**
     * Get the current board.
     *
     * @return Board
     */
    public function getBoard(): Board
    {
        return $this->board;
    }

    /**
     * Check if there's a winner and calculate score.
     *
     * @param array $state
     * @return array|false
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    protected function getWinnerOrDraw(array $state)
    {
        if (count($this->filterAvailableMoves($state)) === 0) {
            return [
                'result' => 'D',
                'who' => null,
                'score' => 0,
                'index' => null,
            ];
        } elseif ($this->isWinner($state, $this->us)) {
            return [
                'result' => 'W',
                'who' => $this->us,
                'score' => 10,
                'index' => null,
            ];
        } elseif ($this->isWinner($state, $this->them)) {
            return [
                'result' => 'W',
                'who' => $this->them,
                'score' => -10,
                'index' => null,
            ];
        }

        return false;
    }

    /**
     * Infer the opponent.
     *
     * @param string $us
     */
    protected function inferOpponent(string $us)
    {
        $this->us = $us;

        $this->them = infer_opponent($us);
    }

    /**
     * Initialize.
     *
     * @param array|string $boardState
     * @param string $playerUnit
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    protected function initialize($boardState, string $playerUnit): void
    {
        $this->board = new Board($boardState);

        $this->inferOpponent($playerUnit);
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
    public function makeMove($boardState, $playerUnit = 'O')
    {
        $this->initialize($boardState, $playerUnit);

        $this->inferOpponent($playerUnit);

        return $this->thinkOurNextMove($playerUnit);
    }

    /**
     * Minimax algorithm.
     *
     * @param array $state
     * @param string $playerUnit
     * @return array|bool
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    protected function minimax(array $state, string $playerUnit)
    {
        if (($result = $this->getWinnerOrDraw($state)) !== false) {
            return $result;
        }

        return $this->selectBestMove(
            $playerUnit,
            $this->calculateWinningMoves($state, $playerUnit)
        );
    }

    /**
     * Select the best next move.
     *
     * @param string $playerUnit
     * @param array $moves
     * @return array
     */
    protected function selectBestMove(string $playerUnit, array $moves): array
    {
        $bestScore = 1000 * ($playerUnit == $this->us ? -1 : 1);

        $moveCount = count($moves);

        $bestMove = 0;

        for ($i = 0; $i < $moveCount; $i++) {
            $currentScore = $moves[$i]['score'];

            $isBestScore =
                $playerUnit == $this->us
                    ? $currentScore > $bestScore
                    : $currentScore < $bestScore;

            if ($isBestScore) {
                $bestScore = $currentScore;

                $bestMove = $i;
            }
        }

        return $moves[$bestMove];
    }

    /**
     * Put AI to decide our next move.
     *
     * @param string $playerUnit
     * @return array
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    protected function thinkOurNextMove(string $playerUnit): array
    {
        $minimax = $this->minimax($this->board->flatten(), $this->us);

        if ($minimax['index'] !== null) {
            return array_merge(
                $this->board->convertTo2DMove($minimax['index']),
                [$playerUnit]
            );
        }

        return [null, null, $playerUnit];
    }

    /**
     * Check if player is a winner.
     *
     * @param array|string $boardState
     * @param string $playerUnit
     * @return bool
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    public function isWinner($boardState, $playerUnit): bool
    {
        return (new Board($boardState))->isWinner($playerUnit);
    }

    /**
     * Play an AI move.
     *
     * @param string|null $playerUnit
     * @param null|array $boarState
     * @return $this
     * @throws \App\Exceptions\MoveNotAvailableException
     * @throws \App\Exceptions\WrongMoveException
     */
    public function play(string $playerUnit = null, $boarState = null)
    {
        $move = $this->makeMove(
            $boarState ?? $this->board->getState(),
            $playerUnit ? $playerUnit : $this->us
        );

        if ($move[0] !== null) {
            $this->board->registerMove($move[0], $move[1], $move[2]);
        }

        return $this;
    }
}
