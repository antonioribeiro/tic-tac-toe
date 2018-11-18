<?php

namespace App\Http\Controllers;

use App\Services\View;
use App\Services\Request;
use Symfony\Component\HttpFoundation\Response;

class Base
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var View
     */
    protected $view;

    /**
     * Base constructor.
     *
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->initialize($request);
    }

    /**
     * Initialize base controller.
     *
     * @param Request|null $request
     */
    private function initialize(Request $request = null)
    {
        $this->view = new View();

        $this->request = $request ?? new Request();
    }

    /**
     * Return a json response.
     *
     * @param array $content
     * @return Response
     */
    public function jsonResponse(array $content): Response
    {
        $response = new Response();

        $response->setContent(json_encode($content));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Get request param.
     *
     * @param string $fieldName
     * @return mixed
     */
    public function getParam(string $fieldName)
    {
        return $this->request->get($fieldName);
    }

    /**
     * Return a response.
     *
     * @param string $content
     * @return Response
     */
    protected function response(string $content)
    {
        return new Response($content, Response::HTTP_OK, array(
            'content-type' => 'text/html',
        ));
    }
}
