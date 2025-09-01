<?php


use Controllers\UserProductsController;
use Controllers\FeedbackController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;
use Core\App;
use Request\RegisrateRequest;
use Request\UserProductsRequest;
require_once './../Core/Autoloader.php';

$path = dirname(__DIR__); //возвращает родительскую директорию
//$path = realpath(__DIR__ . '/../');

\Core\Autoloader::registar($path);

$app = new App();
$app->get('/registration', UserController::class, 'getRegistrate');
$app->post('/registration', UserController::class, 'registrate', RegisrateRequest::class);
$app->get('/login', UserController::class, 'getLogin');
$app->post('/login', UserController::class, 'login', RegisrateRequest::class);
$app->get('/profile', UserController::class, 'getProfile');
$app->get('/edit-profile', UserController::class, 'getEditProfile');
$app->post('/edit-profile', UserController::class, 'editProfile', RegisrateRequest::class);
$app->get('/catalog', ProductController::class, 'getCatalog');
$app->post('/add-cart', UserProductsController::class, 'addCart', UserProductsRequest::class);
$app->post('/decrease-cart', UserProductsController::class, 'decreaseCart', UserProductsRequest::class);
$app->get('/cart', UserProductsController::class, 'getCart');
$app->get('/logout', UserController::class, 'logout');
$app->get('/create-order', OrderController::class, 'getCheckOut');
$app->post('/create-order', OrderController::class, 'handleCheckOut');
$app->get('/orders', OrderController::class, 'getPageOrders');
$app->get('/feedback', FeedbackController::class, 'getFeedback');
$app->post('/feedback', FeedbackController::class, 'getFeedback');
$app->run();
