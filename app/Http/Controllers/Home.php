<?php

namespace App\Http\Controllers;

class Home extends Base
{
    /**
     * Render the home page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->response($this->view->make('home'));
    }
}
