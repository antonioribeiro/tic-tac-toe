<?php

namespace App\Services;

use App\Exceptions\MethodNotFoundException;

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
     *
     * @return mixed
     * @throws MethodNotFoundException
     * @throws \App\Exceptions\MethodNotAllowedException
     * @throws \App\Exceptions\NotFoundHttpException
     */
    public function run()
    {
        return $this->router->call($this->request);
    }
}
