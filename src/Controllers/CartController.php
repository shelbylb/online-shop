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

<<<<<<< Updated upstream
            $userId = $_SESSION['userId'];
            $productId = $_POST['productId'];
            $amount = $_POST['amount'];


            $data = $this->cartModel->checkProduct($productId, $userId);

            if($data === null) {

                $this->cartModel->add($userId, $productId, $amount);

            } else {
                $amount = $data->getAmount() + $amount;
                $this->cartModel->update($userId, $productId, $amount);
=======
                $amount = $this->cartService->addProduct($dto);

                echo json_encode($amount);
>>>>>>> Stashed changes
            }

            header("Location: /catalog");
        }
    }

    private function validateAddCart(array  $data): array
    {
        $errors = [];

        if(isset($data['amount'])){
            $amount = (int)$data['amount'];

<<<<<<< Updated upstream
            if($amount < 0 && $amount > 100){
                $errors['amount'] = 'Введите колличество товара от 1 до 100';
=======
                 $amount = $this->cartService->decreaseProduct($dto);
                echo $amount;
                //header("Location: /cart");
>>>>>>> Stashed changes
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

    public function updateQuantity(UpdateCartRequest $request)
    {
        header('Content-Type: application/json');

        $productId = $request->getProductId();
        $amount = $request->getAmount();

        // Вызываем метод сервиса
        $result = $this->cartService->updateQuantity($productId, $amount);

        echo json_encode($result);
        exit;
    }

}