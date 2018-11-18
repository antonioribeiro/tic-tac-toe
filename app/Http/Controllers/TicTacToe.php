<?php

namespace App\Http\Controllers;

use App\Services\Board;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TicTacToe as TicTacToeService;

class TicTacToe extends Base
{
    /**
     * Get the current opponent.
     *
     * @return string
     */
    protected function getOpponent(): string
    {
        return $this->getParam('player');
    }

    /**
     * Get the current player.
     *
     * @return string
     */
    protected function getPlayer(): string
    {
        return $this->getOpponent() === 'O' ? 'X' : 'O';
    }

    /**
     * Make the board result.
     *
     * @param array $boardState
     * @return array
     * @throws \App\Exceptions\WrongBoardSizeException
     */
    protected function makeBoardResult(array $boardState): array
    {
        $board = new Board($boardState);

        return [
            'board' => $board->getState(),
            'player' => $this->getOpponent(),
            'opponent' => $this->getPlayer(),
            'result' => $board->getResultFor($this->getOpponent()),
            'column' => $board->getColumnResultFor($this->getOpponent()),
            'row' => $board->getRowResultFor($this->getOpponent()),
            'diagonal' => $board->getDiagonalResultFor($this->getOpponent()),
            'finished' => $board->isFinished(),
        ];
    }

    /**
     * Play and send back a new board.
     *
     * @return Response
     * @throws \App\Exceptions\MoveNotAvailableException
     * @throws \App\Exceptions\WrongBoardSizeException
     * @throws \App\Exceptions\WrongMoveException
     */
    public function play(): Response
    {
        return $this->jsonResponse(
            $this->makeBoardResult($this->registerAndPlay())
        );
    }

    /**
     * Register an opponent move and make AI play a move.
     *
     * @return array
     * @throws \App\Exceptions\MoveNotAvailableException
     * @throws \App\Exceptions\WrongBoardSizeException
     * @throws \App\Exceptions\WrongMoveException
     */
    protected function registerAndPlay(): array
    {
        return (new TicTacToeService($this->getParam('board')))
            ->opponentMove(
                $this->getParam('column'),
                $this->getParam('row'),
                $this->getOpponent()
            )
            ->play($this->getPlayer())
            ->getBoard()
            ->getState();
    }
}
