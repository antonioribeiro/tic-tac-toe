<?php

namespace App\Services;

use App\Contracts\MoveInterface;
use App\Services\Traits\AvailableMoves;

class Robot implements MoveInterface
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
     * Calculate next winning moves.
     *
     * @param array $state
     * @param string $playerUnit
     * @return array
     */
    protected function calculateWinningMoves(
        array $state,
        string $playerUnit
    ): array {
        $spots = $this->filterAvailableMoves($state);

        $moves = [];

        for ($i = 0; $i < count($spots); $i++) {
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
     * Check if there's a winner and calculate score.
     *
     * @param array $state
     * @return array|bool
     */
    protected function getWinnerOrDraw(array $state)
    {
        if (count($this->filterAvailableMoves($state)) === 0) {
            return ['score' => 0];
        } elseif ($this->isWinner($state, $this->us)) {
            return ['score' => 10];
        } elseif ($this->isWinner($state, $this->them)) {
            return ['score' => -10];
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

        $this->them = $us === 'X' ? 'O' : 'X';
    }

    /**
     * Initialize.
     *
     * @param array $boardState
     * @param string $playerUnit
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    protected function initialize(array $boardState, string $playerUnit): void
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
    public function makeMove($boardState, $playerUnit = 'X')
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

        $bestMove = 0;

        for ($i = 0; $i < count($moves); $i++) {
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

    // isWinner combinations using the board indexies for instace the first win could be 3 xes in a row

    /**
     * Put AI to decide our next move.
     *
     * @param string $playerUnit
     * @return array
     */
    protected function thinkOurNextMove(string $playerUnit): array
    {
        return array_merge(
            $this->board->convertTo2DMove(
                $this->minimax($this->board->flatten(), $this->us)['index']
            ),
            [$playerUnit]
        );
    }

    function isWinner($board, $playerUnit): bool
    {
        return (new Board($board))->isWinner($playerUnit);
    }
}
