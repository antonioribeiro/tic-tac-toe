<?php

namespace App\Services;

use App\Services\Traits\Actionable;
use App\Exceptions\NotFoundHttpException;
use App\Exceptions\MethodNotAllowedException;

class Router
{
    use Actionable;

    /**
     * @var \FastRoute\Dispatcher
     */
    protected $router;

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
    protected function initialize()
    {
        $this->initializeRoutes();
    }

    /**
     * Initialize all routes.
     */
    protected function initializeRoutes()
    {
        $this->router = \FastRoute\simpleDispatcher(function (
            \FastRoute\RouteCollector $router
        ) {
            $router->addRoute('GET', '/', 'Home@index');

            $router->addRoute('POST', '/play', 'TicTacToe@play');
        });
    }

    /**
     * Match the route coming from a request.
     *
     * @param Request $request
     * @return array
     * @throws MethodNotAllowedException
     * @throws NotFoundHttpException
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
     * @param array $routeInfo
     * @return array
     * @throws MethodNotAllowedException
     * @throws NotFoundHttpException
     */
    protected function routeInfo(array $routeInfo): array
    {
        if ($routeInfo[0] == \FastRoute\Dispatcher::NOT_FOUND) {
            throw new NotFoundHttpException();
        }

        if ($routeInfo[0] == \FastRoute\Dispatcher::METHOD_NOT_ALLOWED) {
            throw new MethodNotAllowedException();
        }

        return ['action' => $routeInfo[1], 'vars' => $routeInfo[2]];
    }
}
