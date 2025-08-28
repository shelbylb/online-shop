<?php

namespace Core;

use Controllers\UserProductsController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;

class App
{

    private array $routes = [];

    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri])) {

            $routesMethod = $this->routes[$requestUri];

            if (isset($routesMethod[$requestMethod])) {

                $handler = $routesMethod[$requestMethod];

                $class = $handler['class'];
                $method = $handler['method'];


                $controller = new $class();
                $controller->$method($_POST);

            } else {
                echo "$routesMethod не поддерживается для $requestUri";
            }

        } else {
            http_response_code(404);
            require_once '../Views/'; //подключить страницу 404
        }


    }

    public function get(string $route, string $className,string $method)
    {
        $this->routes[$route]['GET'] = [
            'class' => $className,
            'method' => $method
        ];
    }

    public function post(string $route, string $className,string $method)
    {
        $this->routes[$route]['POST'] = [
            'class' => $className,
            'method' => $method
        ];
    }

    public function put(string $route, string $className,string $method)
    {
        $this->routes[$route]['PUT'] = [
            'class' => $className,
            'method' => $method
        ];
    }

    public function delete(string $route, string $className,string $method)
    {
        $this->routes[$route]['DELETE'] = [
            'class' => $className,
            'method' => $method
        ];
    }

}