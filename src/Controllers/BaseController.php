<?php

namespace Controllers;

class BaseController
{
    public function __construct()
    {

    }

    public function check():bool
    {
        $this->startSession();
        return isset($_SESSION['userId']);

    }



    protected function startSession()
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

    }

}