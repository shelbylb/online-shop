<?php

namespace Controllers;

use DTO\OrderCreateDTO;
use Model\UserProducts;
use Model\Order;
use Model\OrderProduct;
use Model\Product;


class OrderController extends BaseController
{

    private Order $orderModel;
    private UserProducts $userProduct;
    private OrderProduct $orderProductModel;
    private Product $productModel;



    public function __construct(){
        parent:: __construct();
        $this->orderModel = new Order();
        $this->userProduct = new UserProducts();
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

        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }
        print_r($_POST);


        $errors = $this->validate($_POST);
        print_r($errors);

        if (empty($errors)) {

            $user = $this->authService->getCurrentUser();

            $dto = new OrderCreateDTO(
                $_POST["contact_name"],
                $_POST["contact_phone"],
                $_POST["comment"],
                $_POST["address"],
                $user);

            $this->orderService->createOrder($dto);


        } else {
            require_once '../Views/order_form.php';
        }
    }
    private function validate(array $data): array
    {

        $errors = [];

        if (isset($data['contact_name'])) {

            $name = $data['contact_name'];
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
        if ($this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $userId = $this->authService->check();

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