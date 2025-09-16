<?php

namespace Controllers;

use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;
use Service\OrderService;

abstract class BaseController
{
    protected AuthInterface $authService;



    public function __construct()
    {
        $this->authService = new AuthSessionService();

    }



}
