<?php

namespace App\Services;

use App\Exceptions\MethodNotFoundException;
use App\Exceptions\MethodNotAllowedException;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    /**
     * @var \FastRoute\Dispatcher
     */
    private $router;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Initialize router.
     */
    private function initialize()
    {
        $this->initializeRoutes();
    }

    /**
     * Initialize all routes.
     */
    private function initializeRoutes()
    {
        $this->router = \FastRoute\simpleDispatcher(function (
            \FastRoute\RouteCollector $router
        ) {
            $router->addRoute('GET', '/', 'Home@index');

            $router->addRoute('GET', '/play', 'TicTacToe@play');
        });
    }

    /**
     * Match the route coming from a request.
     *
     * @param Request $request
     * @return array
     * @throws MethodNotAllowedException
     * @throws MethodNotFoundException
     */
    public function match(Request $request)
    {
        return $this->routeInfo($this->matchRoute($request));
    }

    /**
     * Try to match a route.
     *
     * @param Request $request
     * @return array
     */
    protected function matchRoute(Request $request): array
    {
        return $this->router->dispatch(
            $request->getMethod(),
            rawurldecode($request->getPathInfo())
        );
    }

    /**
     * Build matched route info.
     *
     * @param $routeInfo
     * @return array
     * @throws MethodNotAllowedException
     * @throws MethodNotFoundException
     */
    protected function routeInfo($routeInfo): array
    {
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                throw new MethodNotFoundException();
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
            case \FastRoute\Dispatcher::FOUND:
                return ['action' => $routeInfo[1], 'vars' => $routeInfo[2]];
        }
    }
}
