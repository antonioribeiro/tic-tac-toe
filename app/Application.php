<?php

namespace App;

use App\Services\Router;
use App\Services\TicTacToe;
use Symfony\Component\HttpFoundation\Request;

class Application
{
    /**
     * @var TicTacToe
     */
    private $ticTacToe;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Router
     */
    private $router;

    /**
     * Application constructor.
     *
     * @param array $boardState
     * @param int $size
     * @throws Exceptions\WrongBoardSizeException
     */
    public function __construct(array $boardState = [], int $size = 3)
    {
        $this->initialize($boardState, $size);
    }

    /**
     * Get the tic tac toe instance.
     *
     * @return TicTacToe
     */
    public function getTicTacToe(): TicTacToe
    {
        return $this->ticTacToe;
    }

    /**
     * Initialize Tic Tac Toe object.
     *
     * @param array $boardState
     * @param int $size
     * @throws Exceptions\WrongBoardSizeException
     */
    protected function initialize(array $boardState, int $size): void
    {
        $this->ticTacToe = new TicTacToe($boardState, $size);

        $this->request = Request::createFromGlobals();

        $this->router = new Router();
    }

    /**
     * Run the application
     */
    public function run()
    {
        dd($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        dd($this->router->match($this->request));
    }
}
