<?php

namespace App\Services\Traits;

use Closure;
use App\Exceptions\MethodNotFoundException;

trait Actionable
{
    /**
     * Break a controller@action string.
     *
     * @param string $action
     * @return array
     */
    protected function breakControllerAndAction(string $action)
    {
        preg_match('|(.*)@(.*)|', $action, $matches);

        $controllerClass = 'App\\Http\\Controllers\\' . $matches[1];

        $controllerObject = new $controllerClass($this->request);

        return [$controllerClass, $controllerObject, $matches[2]];
    }

    /**
     * Call a controller action.
     *
     * @param array $match
     * @return mixed
     * @throws MethodNotFoundException
     */
    protected function callAction(array $match)
    {
        list(
            $controllerClass,
            $controllerObject,
            $method,
        ) = $this->breakControllerAndAction($match['action']);

        if (method_exists($controllerObject, $method)) {
            return call_user_func_array(
                $this->makeControllerMethodCallback($controllerObject, $method),
                array_merge([$this->request], $match['vars'])
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
