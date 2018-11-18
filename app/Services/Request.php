<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request
{
    protected $request;

    /**
     * @param array                $query      The GET parameters
     * @param array                $request    The POST parameters
     * @param array                $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array                $cookies    The COOKIE parameters
     * @param array                $files      The FILES parameters
     * @param array                $server     The SERVER parameters
     * @param string|resource|null $content    The raw body data
     */
    public function __construct(
        array $query = array(),
        array $request = array(),
        array $attributes = array(),
        array $cookies = array(),
        array $files = array(),
        array $server = array(),
        $content = null
    ) {
        $this->initialize(
            $query,
            $request,
            $attributes,
            $cookies,
            $files,
            $server,
            $content
        );
    }

    /**
     * Create request from constructor call parameters.
     *
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string $content
     * @return SymfonyRequest
     */
    private function createFromConstructor(
        array $query,
        array $request,
        array $attributes,
        array $cookies,
        array $files,
        array $server,
        $content
    ): SymfonyRequest {
        return new SymfonyRequest(
            $query,
            $request,
            $attributes,
            $cookies,
            $files,
            $server,
            $content
        );
    }

    /**
     * Create a request from globals.
     *
     * @return SymfonyRequest
     */
    private function createFromGlobals(): SymfonyRequest
    {
        return SymfonyRequest::createFromGlobals();
    }

    /**
     * Get the request parameters.
     *
     * @param string $paramName
     * @return mixed
     */
    public function get(string $paramName)
    {
        return $this->request->get($paramName);
    }

    /**
     * Gets the request "intended" method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * Returns the path being requested relative to the executed script.
     *
     * @return string
     */
    public function getPathInfo()
    {
        return $this->request->getPathInfo();
    }

    private function initialize(
        array $query,
        array $request,
        array $attributes,
        array $cookies,
        array $files,
        array $server,
        $content
    ) {
        $this->request = $this->isEmptyRequest(
            $query,
            $request,
            $attributes,
            $cookies,
            $files,
            $server,
            $content
        )
            ? $this->createFromGlobals()
            : $this->createFromConstructor(
                $query,
                $request,
                $attributes,
                $cookies,
                $files,
                $server,
                $content
            );
    }

    private function isEmptyRequest(
        array $query,
        array $request,
        array $attributes,
        array $cookies,
        array $files,
        array $server,
        $content
    ) {
        return !$query &&
            !$request &&
            !$attributes &&
            !$cookies &&
            !$files &&
            !$server &&
            !$content;
    }
}
