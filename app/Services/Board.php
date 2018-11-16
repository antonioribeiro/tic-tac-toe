<?php

namespace App\Services;

use App\Exceptions\WrongMoveException;
use App\Exceptions\WrongBoardSizeException;
use App\Exceptions\MoveNotAvailableException;

class Board
{
    /**
     * The current board state.
     *
     * @var array
     */
    protected $boardState;

    /**
     * Board constructor.
     *
     * @param array $boardState
     * @param int $size
     * @throws WrongBoardSizeException
     */
    public function __construct(array $boardState = [], int $size = 3)
    {
        $this->generateBoard($boardState, $size);
    }

    /**
     * UnFlatten a flattened board.
     *
     * @param array $boardState
     * @return array
     */
    public function unFlatten(array $boardState)
    {
        if (
            sizeof($boardState) === 0 ||
            sizeof($boardState) !== sizeof($boardState, 1)
        ) {
            return $boardState;
        }

        $size = (int) sqrt(sizeof($boardState));

        return array_chunk(
            array_map(function ($value) {
                return is_numeric($value) ? '' : $value;
            }, $boardState),
            $size
        );
    }

    /**
     * Check if we have a correct board size.
     *
     * @param array $boardState
     * @throws WrongBoardSizeException
     */
    protected function checkBoardSize(array $boardState): void
    {
        if (count($boardState) > 0 && count($boardState) < 3) {
            throw new WrongBoardSizeException();
        }

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
        return trim($this->boardState[$row][$col]) === '';
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
            $row > $this->getSize() - 1 ||
            $col < 0 ||
            $col > $this->getSize() - 1
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
        $this->checkBoardSize(($boardState = $this->unFlatten($boardState)));

        return $this->boardState =
            count($boardState) == 0
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
    protected function getSize(): int
    {
        return count($this->boardState);
    }

    /**
     * Get the current board state.
     *
     * @return array
     */
    public function getState(): array
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
     * @return Board
     * @throws WrongMoveException
     * @throws MoveNotAvailableException
     */
    public function registerMove(int $col, int $row, string $playerUnit): Board
    {
        $this->throwIfMoveIsNotGood($col, $row);

        $this->throwIfMoveIsNotAvailable($col, $row);

        $this->boardState[$row][$col] = $playerUnit;

        return $this;
    }

    /**
     * Flatten the board.
     *
     * @return array
     */
    public function flatten(): array
    {
        $state = array_merge(...$this->getState());

        $counter = 0;

        foreach ($state as $key => $item) {
            if ($item === '') {
                $state[$key] = $counter;
            }

            $counter++;
        }

        return $state;
    }

    /**
     * Check if player wins the game.
     *
     * @param string $playerUnit
     * @return bool
     */
    public function isWinner(string $playerUnit)
    {
        return $this->winByRow($playerUnit) ||
            $this->winByColumn($playerUnit) ||
            $this->winByDiagonal($playerUnit);
    }

    /**
     * Check if player wins by a column.
     *
     * @param string $playerUnit
     * @return bool
     */
    protected function winByColumn(string $playerUnit): bool
    {
        foreach (range(0, $this->getSize() - 1) as $column) {
            if (
                implode('', array_column($this->getState(), $column)) ===
                $this->getWinnerResult($playerUnit)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if player wins by a diagonal.
     *
     * @param string $playerUnit
     * @return bool
     */
    protected function winByDiagonal(string $playerUnit): bool
    {
        $d1 = '';
        $d2 = '';

        for ($x = 0; $x <= $this->getSize() - 1; $x++) {
            $d1 .= $this->getState()[$x][$x];
        }

        for ($x = 0; $x <= $this->getSize() - 1; $x++) {
            $d2 .= $this->getState()[$x][$this->getSize() - 1 - $x];
        }

        return $d1 === $this->getWinnerResult($playerUnit) ||
            $d2 === $this->getWinnerResult($playerUnit);
    }

    /**
     * Check if player wins by a row.
     *
     * @param string $playerUnit
     * @return bool
     */
    protected function winByRow(string $playerUnit): bool
    {
        foreach ($this->getState() as $row) {
            if (implode('', $row) === $this->getWinnerResult($playerUnit)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the winner result for a player.
     *
     * @param string $playerUnit
     * @return string
     */
    protected function getWinnerResult(string $playerUnit): string
    {
        return str_repeat($playerUnit, $this->getSize());
    }

    /**
     * Check if the board still has available moves.
     *
     * @return bool
     */
    public function hasAvailableMoves(): bool
    {
        return strlen(implode('', $this->flatten())) !== $this->getSize();
    }

    /**
     * Get flatten array with available moves.
     */
    public function getAvailableMoves(): array
    {
        return extract_available_moves($this->flatten());
    }

    /**
     * Convert a 1D move to a 2D.
     *
     * @param int $move
     * @return array
     */
    public function convertTo2DMove(int $move): array
    {
        return [$move % $this->getSize(), abs($move / $this->getSize())];
    }
}
