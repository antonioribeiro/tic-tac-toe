<?php

namespace App\Services\Traits;

trait Winnable
{
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
    abstract protected function getSize(): int;

    /**
     * Get the current board state.
     *
     * @return array
     */
    abstract public function getState(): array;

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
    abstract public function hasAvailableMoves(): bool;

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
