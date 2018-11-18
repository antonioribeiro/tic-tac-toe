<?php

namespace App\Services\Traits;

use Closure;
use App\Services\Request;
use App\Exceptions\MethodNotFoundException;

trait Actionable
{
    /**
     * Break a controller@action string.
     *
     * @param Request $request
     * @return array
     */
    protected function matchToControllerMethod(Request $request)
    {
        $routerMatched = $this->match($request);

        preg_match(
            '|(.*)@(.*)|',
            $routerMatched['action'],
            $controllerAndAction
        );

        $controllerClass = 'App\\Http\\Controllers\\' . $controllerAndAction[1];

        $controllerObject = new $controllerClass($request);

        return [$controllerClass, $controllerObject, $controllerAndAction[2]];
    }

    /**
     * Call a controller action.
     *
     * @param Request $request
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function call(Request $request)
    {
        list(
            $controllerClass,
            $controllerObject,
            $method,
        ) = $this->matchToControllerMethod($request);

        return $this->callControllerMethod(
            $controllerClass,
            $controllerObject,
            $method,
            $request
        );
    }

    /**
     * Call a controller method.
     *
     * @param string $controllerClass
     * @param object $controllerObject
     * @param string $method
     * @param Request $request
     * @return mixed
     * @throws MethodNotFoundException
     */
    public function callControllerMethod(
        string $controllerClass,
        object $controllerObject,
        string $method,
        Request $request
    ) {
        if (method_exists($controllerObject, $method)) {
            return call_user_func_array(
                $this->makeControllerMethodCallback($controllerObject, $method),
                [$request]
            );
        }

        throw new MethodNotFoundException(
            "Method not found: {$controllerClass}@{$method}"
        );
    }

    /**
     * Make a controller callable method closure.
     *
     * @param object $controllerObject
     * @param string $method
     * @return Closure
     */
    protected function makeControllerMethodCallback(
        object $controllerObject,
        string $method
    ): Closure {
        return function (...$parameters) use ($controllerObject, $method) {
            return $controllerObject->$method(...$parameters);
        };
    }
}
