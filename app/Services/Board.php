<?php

namespace App\Services;

use App\Services\Traits\Movable;
use App\Services\Traits\Winnable;
use App\Services\Traits\AvailableMoves;
use App\Exceptions\WrongBoardSizeException;

class Board
{
    use AvailableMoves, Movable, Winnable;

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
     * Set the board state.
     *
     * @param array $boardState
     */
    public function setState(array $boardState): void
    {
        $this->boardState = $boardState;
    }

    protected function strigBoardStateToArray($boardState)
    {
        if (is_array($boardState)) {
            return $boardState;
        }

        return explode(',', $boardState);
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
}
