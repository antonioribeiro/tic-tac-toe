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
     * @param string $name
     * @return string
     */
    public function make($name)
    {
        return ($view = file_get_contents(
            $this->getViewDirectory() . "/{$name}.html"
        ))
            ? $view
            : '';
    }
}
