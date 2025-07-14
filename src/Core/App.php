<?php

namespace Core;

use Controllers\CartController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;

class App
{
//    private array $routes = [
//        '/registration' => [
//            'GET' => [
//                'class' => UserController::class,
//                'method' => 'getRegistrate',
//            ],
//            'POST' => [
//                'class' => UserController::class,
//                'method' => 'registrate',
//            ]
//        ],
//
//        '/login' => [
//            'GET' => [
//                'class' => UserController::class,
//                'method' => 'getLogin',
//            ],
//            'POST' => [
//                'class' => UserController::class,
//                'method' => 'login',
//            ]
//        ],
//
//        '/profile' => [
//            'GET' => [
//                'class' => UserController::class,
//                'method' => 'getProfile',
//            ]
//        ],
//
//        '/edit-profile' => [
//            'GET' => [
//                'class' => UserController::class,
//                'method' => 'getEditProfile',
//            ],
//            'POST' => [
//                'class' => UserController::class,
//                'method' => 'editProfile',
//            ]
//        ],
//
//        '/catalog' => [
//            'GET' => [
//                'class' => ProductController::class,
//                'method' => 'getCatalog',
//            ]
//        ],
//
//        '/add-cart' => [
//            'POST' => [
//                'class' => CartController::class,
//                'method' => 'addCart',
//            ]
//        ],
//
//        '/cart' => [
//            'GET' => [
//                'class' => CartController::class,
//                'method' => 'getCart',
//            ]
//        ],
//
//        '/logout' => [
//            'GET' => [
//                'class' => UserController::class,
//                'method' => 'logout',
//            ]
//        ],
//
//        '/create-order' => [
//            'GET' => [
//                'class' => OrderController::class,
//                'method' => 'getCheckOut',
//
//            ],
//
//            'POST' => [
//                'class' => OrderController::class,
//                'method' => 'handleCheckOut',
//            ]
//        ],
//
//        '/orders' => [
//            'GET' => [
//                'class' => OrderController::class,
//                'method' => 'getPageOrders',
//
//            ],
//
//            'POST' => [
//                'class' => OrderController::class,
//                'method' => '',
//            ]
//        ]
//    ];

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
                $controller->$method();

            } else {
                echo "$routesMethod не поддерживаетсядля $requestUri";
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