<?php

namespace App\Services;

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
        return file_get_contents($this->getViewDirectory() . "/{$name}.html");
    }
}
