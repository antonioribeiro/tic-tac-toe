<?php

namespace App\Services\Traits;

use App\Services\Board;
use App\Exceptions\WrongMoveException;
use App\Exceptions\MoveNotAvailableException;

trait Movable
{
    /**
     * Extract available moves from a flattened state array.
     *
     * @param array $state
     * @return array
     */
    abstract public function filterAvailableMoves(array $state): array;

    /**
     * Flatten the board.
     *
     * @return array
     */
    abstract public function flatten(): array;

    /**
     * Get flatten array with available moves.
     */
    public function getAvailableMoves(): array
    {
        return $this->filterAvailableMoves($this->flatten());
    }

    /**
     * Get the current board size.
     *
     * @return int
     */
    abstract protected function getSize(): int;

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
}
