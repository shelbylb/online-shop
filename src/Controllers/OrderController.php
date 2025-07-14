<?php

namespace Controllers;

use Model\Cart;
use Model\Order;
use Model\OrderProduct;
use Model\Product;


class OrderController
{

    private Order $orderModel;
    private Cart $cartModel;
    private OrderProduct $orderProductModel;

    private Product $productModel;


    public function __construct(){
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();
    }

    public function getCheckOut()
    {
        require_once '../Views/order_form.php';

    }

    public function getPageOrders()
    {
        require_once '../Views/orders.php';

    }

    public function handleCheckOut()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }


        $errors = $this->validate($_POST);

        if (empty($errors)) {

            $contactName = $_POST["contact_name"];
            $contactPhone = $_POST["contact_phone"];
            $comment = $_POST["comment"];
            $address = $_POST["address"];
            $userId = $_SESSION['userId'];

            $orderId = $this->orderModel->create($contactName, $contactPhone, $comment, $address, $userId);

            $userProducts = $this->cartModel->getAllUserProductsByUserId($userId);


            foreach ($userProducts as $userProduct) {
                $productId = $userProduct->getProductId();
                $amount = $userProduct->getAmount();

                $this->orderProductModel->create($orderId, $productId, $amount);
            }

            //удаляет товары из корзины
            $this->cartModel->deleteByUserId($userId);


        } else {
            require_once '../Views/order_form.php';
        }
    }
    private function validate(array $data): array
    {
//сделать валидацию по заполняемым данным в форме заказа
        $errors = [];

        if (isset($data['name'])) {

            $name = $data["name"];
            if (strlen($name) < 2) {
                $errors['name'] = 'Имя должно быть больше двух символов';
            }
        } else {
            $errors['name'] = 'Поле  должно быть заполнено';
        }


        if (isset($data['contact_phone'])) {
            $contactPhone = $data["contact_phone"];
            if (strlen($contactPhone) === 11) {
                $errors['contact_phone'] = 'Номер должен состоять из 11 симовлов';

            }
        } else {
            $errors['contact_phone'] = 'Поле  должно быть заполнено';
        }

        if (!isset($data['address'])) {


            $errors['address'] = 'Поле  должно быть заполнено';
        }

        return $errors;
    }


    public function getAllOrders()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['userId'])) {
            header("Location: /login");
            exit();
        }

        $userId = $_SESSION['userId'];

        $userOrders = $this->orderModel->getAllByUserId($userId);

        $newUserOders = [];
        $newOrderProducts=[];
        $sum = 0;

        foreach ($userOrders as $userOrder) {
            $orderId = $userOrder['id'];


            $orderProducts = $this->orderProductModel->getAllByOrderId($orderId);

            foreach ($orderProducts as $orderProduct) {
                $productId = $orderProduct->getProductId();

                $product = $this->productModel->getOneById($productId);

                $orderProduct['name'] = $product->getName();
                $orderProduct['price'] = $product->getPrice();
                $orderProduct['totalSum']= $product->getPrice() * $orderProduct['amount'];

                $newOrderProducts[] = $orderProduct;

                $sum = $sum + $orderProduct['totalSum'];
            }



            $userOrder['orderProducts'] = $newOrderProducts;
            $userOrder['total'] = $sum;

            $newUserOders[] = $userOrder;
        }


    }





}