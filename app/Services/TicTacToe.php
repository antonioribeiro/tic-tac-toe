<?php

namespace App\Services;

use App\Contracts\MoveInterface;
use App\Exceptions\WrongMoveException;
use App\Exceptions\WrongBoardSizeException;
use App\Exceptions\MoveNotAvailableException;

class TicTacToe implements MoveInterface
{
    /**
     * The current board state.
     *
     * @var array
     */
    protected $boardState;

    /**
     * TicTacToe constructor.
     *
     * @param array $boardState
     * @param int $size
     * @throws WrongBoardSizeException
     */
    public function __construct(array $boardState = [], int $size = 3)
    {
        $this->boardState = $this->generateBoard($boardState, $size);
    }

    /**
     * Check if we have a correct board size.
     *
     * @param array $boardState
     * @throws WrongBoardSizeException
     */
    protected function checkBoardStateSize(array $boardState): void
    {
        // If board is null, no need to throw exceptions
        foreach ($boardState as $line) {
            if (count($line) !== count($boardState)) {
                throw new WrongBoardSizeException();
            }
        }
    }

    /**
     * Check if move is available.
     *
     * @param int $col
     * @param int $row
     * @return bool
     */
    protected function moveIsAvailable(int $col, int $row): bool
    {
        return $this->boardState[$row][$col] == '';
    }

    /**
     * If move is not available, throw exception.
     *
     * @param int $col
     * @param int $row
     * @throws MoveNotAvailableException
     */
    protected function throwIfMoveIsNotAvailable(int $col, int $row)
    {
        if (!$this->moveIsAvailable($col, $row)) {
            throw new MoveNotAvailableException();
        }
    }

    /**
     * Check if a move is currently available.
     *
     * @param int $col
     * @param int $row
     * @throws WrongMoveException
     */
    protected function throwIfMoveIsNotGood(int $col, int $row)
    {
        if (
            $row < 0 ||
            $row > $this->getBoardSize() - 1 ||
            $col < 0 ||
            $col > $this->getBoardSize() - 1
        ) {
            throw new WrongMoveException();
        }
    }

    /**
     * Generate or use a defined board state.
     *
     * @param array $boardState
     * @param int $size
     * @return array
     * @throws WrongBoardSizeException
     */
    protected function generateBoard(array $boardState, int $size): array
    {
        $this->checkBoardStateSize($boardState);

        return count($boardState) == 0
            ? $this->generateEmptyBoardState($size)
            : $boardState;
    }

    /**
     * Generate an empty board state
     *
     * @param int $size
     * @return array
     */
    protected function generateEmptyBoardState(int $size): array
    {
        $state = [];

        foreach (range(0, $size - 1) as $row) {
            foreach (range(0, $size - 1) as $col) {
                $state[$row][$col] = '';
            }
        }

        return $state;
    }

    /**
     * Get the current board size.
     *
     * @return int
     */
    protected function getBoardSize(): int
    {
        return count($this->boardState);
    }

    /**
     * Board State getter
     *
     * @return array
     */
    public function getBoardState(): array
    {
        return $this->boardState;
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
        return $this->boardState;
    }

    /**
     * Register a move in the current board.
     *
     * @param int $col
     * @param int $row
     * @param string $playerUnit
     * @return TicTacToe
     * @throws WrongMoveException
     * @throws MoveNotAvailableException
     */
    public function registerMove(
        int $col,
        int $row,
        string $playerUnit
    ): TicTacToe {
        $this->throwIfMoveIsNotGood($col, $row);

        $this->throwIfMoveIsNotAvailable($col, $row);

        $this->boardState[$row][$col] = $playerUnit;

        return $this;
    }
}
