<?php

namespace App\Http\Controllers;

use App\Services\TicTacToe as TicTacToeService;
use Symfony\Component\HttpFoundation\Response;

class TicTacToe extends Base
{
    /**
     * Make the board result.
     *
     * @param TicTacToeService $ticTacToe
     * @return array
     */
    protected function makeBoardResult(TicTacToeService $ticTacToe): array
    {
        return [
            'board' => $ticTacToe->getBoard()->getState(),
            'player' => $ticTacToe->getOpponent(),
            'opponent' => $ticTacToe->getPlayer(),
            'result' => $ticTacToe
                ->getBoard()
                ->getResultFor($ticTacToe->getOpponent()),
            'column' => $ticTacToe
                ->getBoard()
                ->getColumnResultFor($ticTacToe->getOpponent()),
            'row' => $ticTacToe
                ->getBoard()
                ->getRowResultFor($ticTacToe->getOpponent()),
            'diagonal' => $ticTacToe
                ->getBoard()
                ->getDiagonalResultFor($ticTacToe->getOpponent()),
            'finished' => $ticTacToe->getBoard()->isFinished(),
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
            $this->makeBoardResult(
                (new TicTacToeService($this->getParam('board')))
                    ->setPlayer(infer_opponent($this->getParam('player')))
                    ->opponentMove(
                        $this->getParam('column'),
                        $this->getParam('row')
                    )
                    ->play()
            )
        );
    }
}
