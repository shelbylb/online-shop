<?php

namespace Controllers;

use Model\User;
use Service\AuthService;
use Service\OrderService;

abstract class BaseController
{
    protected AuthService $authService;
    protected OrderService $orderService;


    public function __construct()
    {
        $this->authService = new AuthService();
        $this->orderService = new OrderService();
    }



}
