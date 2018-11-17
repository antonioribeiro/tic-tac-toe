<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;

class View
{
    /**
     * @return string
     */
    protected function getViewDirectory(): string
    {
        return __DIR__ . "/../../resources/views";
    }

    public function make($name)
    {
        return $this->makeResponse(
            file_get_contents($this->getViewDirectory() . "/{$name}.html")
        );
    }

    private function makeResponse($content)
    {
        return new Response($content, Response::HTTP_OK, array(
            'content-type' => 'text/html',
        ));
    }
}
