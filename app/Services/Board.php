<?php

namespace App\Services;

use App\Exceptions\WrongMoveException;
use App\Services\Traits\AvailableMoves;
use App\Exceptions\WrongBoardSizeException;
use App\Exceptions\MoveNotAvailableException;

class Board
{
    use AvailableMoves;

    /**
     * The current board state.
     *
     * @var array
     */
    protected $boardState;

    /**
     * Board constructor.
     *
     * @param array|string $boardState
     * @param int $size
     * @throws WrongBoardSizeException
     */
    public function __construct($boardState = [], int $size = 3)
    {
        $this->generateBoard($boardState, $size);
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
     * Convert a 1D move to a 2D.
     *
     * @param int $move
     * @return array
     */
    public function convertTo2DMove(int $move): array
    {
        return [$move % $this->getSize(), abs($move / $this->getSize())];
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
     * Generate or use a defined board state.
     *
     * @param array|string $boardState
     * @param int $size
     * @return array
     * @throws WrongBoardSizeException
     */
    protected function generateBoard($boardState, int $size): array
    {
        $this->checkBoardSize(
            ($boardState = $this->unFlatten(
                $this->strigBoardStateToArray($boardState)
            ))
        );

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
     * Get flatten array with available moves.
     */
    public function getAvailableMoves(): array
    {
        return $this->filterAvailableMoves($this->flatten());
    }

    /**
     * Get column result for.
     *
     * @param string $playerUnit
     * @return bool
     */
    public function getColumnResultFor(string $playerUnit = 'O'): bool
    {
        return $this->winByColumn($playerUnit) !== false ?:
            $this->winByColumn(infer_opponent($playerUnit)) !== false;
    }

    /**
     * Get column result for.
     *
     * @param string $playerUnit
     * @return bool
     */
    public function getDiagonalResultFor(string $playerUnit = 'O'): bool
    {
        return $this->winByDiagonal($playerUnit) !== false ?:
            $this->winByDiagonal(infer_opponent($playerUnit)) !== false;
    }

    /**
     * Get the result letter for a player in the current board.
     *
     * @param string $playerUnit
     * @return string|null
     */
    public function getResultFor(string $playerUnit = 'O')
    {
        return $this->isDraw()
            ? 'D'
            : ($this->isWinner($playerUnit)
                ? 'W'
                : ($this->isWinner(infer_opponent($playerUnit))
                    ? 'L'
                    : null));
    }

    /**
     * Get column result for.
     *
     * @param string $playerUnit
     * @return bool
     */
    public function getRowResultFor(string $playerUnit = 'O'): bool
    {
        return $this->winByRow($playerUnit) !== false ?:
            $this->winByRow(infer_opponent($playerUnit)) !== false;
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
        return count($this->getAvailableMoves()) > 0;
    }

    /**
     * Check if it's a draw.
     *
     * @return bool
     */
    public function isDraw()
    {
        return !$this->hasAvailableMoves() &&
            !$this->isWinner('X') &&
            !$this->isWinner('O');
    }

    /**
     * Check if the game has finished
     *
     * @return bool
     */
    public function isFinished()
    {
        return $this->isWinner('X') || $this->isWinner('O') || $this->isDraw();
    }

    /**
     * Check if player wins the game.
     *
     * @param string $playerUnit
     * @return bool
     */
    public function isWinner(string $playerUnit)
    {
        return $this->winByRow($playerUnit) !== false ||
            $this->winByColumn($playerUnit) !== false ||
            $this->winByDiagonal($playerUnit) !== false;
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

    protected function strigBoardStateToArray($boardState)
    {
        if (is_array($boardState)) {
            return $boardState;
        }

        return explode(',', $boardState);
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
     * Check if player wins by a column.
     *
     * @param string $playerUnit
     * @return bool|int
     */
    protected function winByColumn(string $playerUnit)
    {
        foreach (range(0, $this->getSize() - 1) as $key => $column) {
            if (
                implode('', array_column($this->getState(), $column)) ===
                $this->getWinnerResult($playerUnit)
            ) {
                return $key;
            }
        }

        return false;
    }

    /**
     * Check if player wins by a diagonal.
     *
     * @param string $playerUnit
     * @return bool|int
     */
    protected function winByDiagonal(string $playerUnit)
    {
        $d0 = '';
        $d1 = '';

        for ($x = 0; $x <= $this->getSize() - 1; $x++) {
            $d0 .= $this->getState()[$x][$x];
        }

        for ($x = 0; $x <= $this->getSize() - 1; $x++) {
            $d1 .= $this->getState()[$x][$this->getSize() - 1 - $x];
        }

        return $d0 === $this->getWinnerResult($playerUnit)
            ? 0
            : ($d1 === $this->getWinnerResult($playerUnit)
                ? 1
                : false);
    }

    /**
     * Check if player wins by a row.
     *
     * @param string $playerUnit
     * @return bool|int
     */
    protected function winByRow(string $playerUnit)
    {
        foreach ($this->getState() as $key => $row) {
            if (implode('', $row) === $this->getWinnerResult($playerUnit)) {
                return $key;
            }
        }

        return false;
    }
}
