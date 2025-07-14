<?php

namespace Controllers;
use Model\Cart;

class CartController
{
    private Cart $cartModel;

    public function __construct(){
        $this-> cartModel = new Cart();
    }

    public function addCart()
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['userId'])){
            header("Location: /login_form.php");
            exit();
        }

        $errors = $this -> validateAddCart($_POST);

        if(empty($errors)){

            $userId = $_SESSION['userId'];
            $productId = $_POST['productId'];
            $amount = $_POST['amount'];


            $data = $this->cartModel->checkProduct($productId, $userId);

            if($data === null) {

                $this->cartModel->add($userId, $productId, $amount);

            } else {
                $amount = $data->getAmount() + $amount;
                $this->cartModel->update($userId, $productId, $amount);
            }

            header("Location: /catalog");
        }
    }

    private function validateAddCart(array  $data): array
    {
        $errors = [];

        if(isset($data['amount'])){
            $amount = (int)$data['amount'];

            if($amount < 0 && $amount > 100){
                $errors['amount'] = 'Введите колличество товара от 1 до 100';
            }
        }

        return $errors;
    }

    public function getCart(){

        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['userId'])){
            header("Location: /login");
            exit();
        }



        $userId = $_SESSION['userId'];

        /** @var Cart[] $userProducts */
        $userProducts = $this ->cartModel ->getAllUserProductsByUserId($userId);

        $productsCart = [];
        foreach($userProducts as $userProduct)
        {
            //$productId = $userProduct->getProductId();
            $product = $this ->cartModel ->getByAmount($userProduct);
            $product['amount'] = $userProduct->getAmount(); //нужен сеттер
            $productsCart[] = $product;



        }



        require_once '../Views/cart.php';
    }

}