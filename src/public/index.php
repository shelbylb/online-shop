<?php


use Controllers\CartController;
use Controllers\FeedbackController;
use Controllers\OrderController;
use Controllers\ProductController;
use Controllers\UserController;
use Core\App;
use Request\RegisrateRequest;
use Request\AddCartRequest;
require_once './../Core/Autoloader.php';

$path = dirname(__DIR__); //возвращает родительскую директорию
//$path = realpath(__DIR__ . '/../');

\Core\Autoloader::registar($path);

$app = new App();
$app->get('/registration', UserController::class, 'getRegistrate');
$app->post('/registration', UserController::class, 'registrate', RegisrateRequest::class);
$app->get('/login', UserController::class, 'getLogin');
$app->post('/login', UserController::class, 'login', \Request\LoginRequest::class);
$app->get('/profile', UserController::class, 'getProfile');
$app->get('/edit-profile', UserController::class, 'getEditProfile');
$app->post('/edit-profile', UserController::class, 'editProfile', \Request\EditProfileRequest::class);
$app->get('/catalog', ProductController::class, 'getCatalog');
$app->post('/add-cart', CartController::class, 'addCart', AddCartRequest::class);
$app->post('/decrease-cart', CartController::class, 'decreaseCart', \Request\DecreaseCartRequest::class);
$app->get('/cart', CartController::class, 'getCart');
$app->get('/logout', UserController::class, 'logout');
$app->get('/create-order', OrderController::class, 'getCheckOut');
$app->post('/create-order', OrderController::class, 'handleCheckOut', \Request\HandleCheckOutRequest::class);
$app->get('/orders', OrderController::class, 'getPageOrders');
$app->post('/feedback', FeedbackController::class, 'getFeedback', \Request\GetFeedbackRequest::class);
$app->post('/add-feedback', FeedbackController::class, 'addFeedback', \Request\AddFeedbackRequest::class);
$app->run();
