<?php

namespace App;

use App\Services\Router;
use Symfony\Component\HttpFoundation\Request;

class Application
{
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
     */
    public function __construct()
    {
        $this->initialize();
    }

    private function breakControllerAndAction($action)
    {
        preg_match('|(.*)@(.*)|', $action, $matches);

        return [$matches[1], $matches[2]];
    }

    private function callAction(array $match)
    {
        list($controller, $method) = $this->breakControllerAndAction(
            $match['action']
        );

        $controller = '\\App\\Http\\Controllers\\' . $controller;

        return call_user_func_array(
            [new $controller(), $method],
            $match['vars']
        );
    }

    /**
     * Initialize Tic Tac Toe object.
     */
    protected function initialize(): void
    {
        $this->request = Request::createFromGlobals();

        $this->router = new Router();
    }

    /**
     * Run the application
     */
    public function run()
    {
        return $this->callAction($this->router->match($this->request));
    }
}
