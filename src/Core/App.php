<?php

namespace Core;

use Controllers\CartController;
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
                $requestClass =$handler['request'];
                try
                {
                    if($requestClass !== null){
                        $request = new $requestClass($_POST);
                        $controller->$method($request);
                    }else{

                        $controller->$method();
                    }
                } catch (\Throwable $exception)
                {
                    $filename = '../Storage/Log/errors.txt';
                    date_default_timezone_set('Etc/GMT-8');
                    $datetime = date('d.m.y H:i');

                    file_put_contents($filename,PHP_EOL . 'Время: '. $datetime . PHP_EOL, FILE_APPEND);
                    file_put_contents($filename,'Сообщение: '. $exception->getMessage(). PHP_EOL, FILE_APPEND);
                    file_put_contents($filename, 'Файл: '. $exception->getFile(). PHP_EOL, FILE_APPEND);
                    file_put_contents($filename, 'Строка: '. $exception->getLine(). PHP_EOL, FILE_APPEND);
                    require_once '../Views/500.php';
                }


            } else {
                print_r( "$requestMethod не поддерживается для $requestUri");
            }

        } else {
            http_response_code(404);
            require_once '../Views/404.php'; //подключить страницу 404
        }


    }

    public function get(string $route, string $className,string $method, string $requestClass = null)
    {
        $this->routes[$route]['GET'] = [
            'class' => $className,
            'method' => $method,
            'request' => $requestClass
        ];
    }

    public function post(string $route, string $className,string $method, string $requestClass = null)
    {
        $this->routes[$route]['POST'] = [
            'class' => $className,
            'method' => $method,
            'request' => $requestClass
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