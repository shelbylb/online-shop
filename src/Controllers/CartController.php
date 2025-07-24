<?php

namespace Controllers;

use Model\UserProducts;
use Service\AuthService;
use Service\CartService;

class CartController extends BaseController
{
    private UserProducts $cartModel;
    private CartService $cartService;

    public function __construct()
    {
        parent:: __construct();
        $this->cartModel = new UserProducts();
        $this->cartService = new CartService();
    }

    public function addCart()
    {
        if ($this->authService->check()) {

            $errors = $this->validateAddCart($_POST);
            $user = $this->authService->getCurrentUser();
            $data = $_POST;

            if (empty($errors)) {

                $this->cartService->addProduct($data['productId'], $data['userId'], $data ['amount']);

            }

            header("Location: /catalog");
        } else {
            header("Location: /login");
            exit();
        }
    }

    public function deleteCart()
    {
        if ($this->authService->check()) {

            $errors = $this->validateAddCart($_POST);
            $user = $this->authService->getCurrentUser();
            $data = $_POST;

            if (empty($errors)) {

                $this->cartService->decreaseProduct($data['productId'], $data['userId'], $data ['amount']);

            }

            header("Location: /catalog");
        } else {
            header("Location: /login");
            exit();
        }
    }

    private function validateAddCart(array $data): array
    {
        $errors = [];

        if (isset($data['amount'])) {
            $amount = (int)$data['amount'];

            if ($amount < 0 && $amount > 100) {
                $errors['amount'] = 'Введите колличество товара от 1 до 100';
            }
        }

        return $errors;
    }

    public function getCart()
    {

        if ($this->authService->check()) {


            $user = $this->authService->getCurrentUser();


            $userProducts = $this->cartModel->getAllUserProductsByUserId($user->getId());

            $productsCart = [];
            foreach ($userProducts as $userProduct) {
                $productId = $this->cartModel->getProductId();
                $product = $this->cartModel->getByAmount($productId);
                $product['amount'] = $this->cartModel->getAmount();
                $productsCart[] = $product;
            }
            require_once '../Views/cart.php';
        } else {
            header("Location: /login");
            exit();
        }

    }

}