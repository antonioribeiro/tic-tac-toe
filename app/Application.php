<?php

namespace App;

use App\Services\Router;
use App\Services\Request;

class Application
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Router
     */
    protected $router;

    /**
     * Application constructor.
     *
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->initialize($request);
    }

    /**
     * Break a controller@action string.
     *
     * @param string $action
     * @return array
     */
    protected function breakControllerAndAction(string $action)
    {
        preg_match('|(.*)@(.*)|', $action, $matches);

        return [$matches[1], $matches[2]];
    }

    /**
     * Call a controller action.
     *
     * @param array $match
     * @return mixed
     */
    protected function callAction(array $match)
    {
        list($controller, $method) = $this->breakControllerAndAction(
            $match['action']
        );

        $controller = '\\App\\Http\\Controllers\\' . $controller;

        if (
            is_callable(
                ($callable = [new $controller($this->request), $method])
            )
        ) {
            return call_user_func_array(
                $callable,
                array_merge([$this->request], $match['vars'])
            );
        }
    }

    /**
     * Initialize application.
     *
     * @param Request|null $request
     */
    protected function initialize(Request $request = null): void
    {
        $this->request = $request ?? new Request();

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
