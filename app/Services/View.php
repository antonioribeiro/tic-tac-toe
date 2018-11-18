<?php

namespace App\Services;

class View
{
    /**
     * Get the current view directory.
     *
     * @return string
     */
    protected function getViewDirectory(): string
    {
        return __DIR__ . "/../../resources/views";
    }

    /**
     * Make a view.
     *
     * @param $name
     * @return false|string
     */
    public function make($name)
    {
        return file_get_contents($this->getViewDirectory() . "/{$name}.html");
    }
}
