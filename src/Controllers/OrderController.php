<?php

namespace Controllers;

use DTO\OrderCreateDTO;
use Model\Product;
use Request\HandleCheckOutRequest;
use Service\CartService;
use Service\OrderService;


class OrderController extends BaseController
{

    private CartService $cartService;
    protected OrderService $orderService;
    private $productModel;


    public function __construct(){
        parent:: __construct();
        $this->cartService = new CartService();
        $this->orderService = new OrderService();
        $this->productModel = new Product();


    }

    public function getCheckOut()
    {
        if($this->authService->check()){
            $userProducts = $this->cartService->getUserProducts();
            if(empty($userProducts)){
                header('Location: /catalog');
                exit();
            }
            $totalPrice = $this->cartService->getSum();
            require_once '../Views/order_form.php';
        } else{
            header('Location: /login');
            exit();
        }

    }

    public function getPageOrders()
    {
        if (!$this->authService->check()) {
            header("Location: /login");
            exit();
        }


        $userOrders = $this->orderService->getAll();
        echo  '<pre>';
        require_once '../Views/orders.php';

    }

    public function handleCheckOut(HandleCheckOutRequest $request)
    {

        if (!$this->authService->check()) {
            header("Location: /login");
            exit();
        }


        $errors = $request->validate();

        if (empty($errors)) {

            $dto = new OrderCreateDTO(
                $request->getContactName(),
                $request->getContactPhone(),
                $request->getComment(),
                $request->getAddress());

            $this->orderService->createOrder($dto);

        } else {
            $userProducts = $this->cartService->getUserProducts();
            $totalSum =$this->cartService->getSum();

            require_once '../Views/order_form.php';
        }

        header('Location: /catalog');
    }



/*    public function getAllOrders()
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


    }*/





}