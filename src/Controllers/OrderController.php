<?php

namespace Controllers;

use DTO\OrderCreateDTO;
use Model\UserProducts;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Request\HandleCheckOutRequest;
use Service\CartService;


class OrderController extends BaseController
{

    private Order $orderModel;
    private UserProducts $userProduct;
    private OrderProduct $orderProductModel;
    private Product $productModel;
    private CartService $cartService;



    public function __construct(){
        parent:: __construct();
        $this->orderModel = new Order();
        $this->userProduct = new UserProducts();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();
        $this->cartService = new CartService();


    }

    public function getCheckOut()
    {
        require_once '../Views/order_form.php';

    }

    public function getPageOrders()
    {
        require_once '../Views/orders.php';

    }

    public function handleCheckOut(HandleCheckOutRequest $request)
    {

        if (!$this->authService->check()) {
            header("Location: /login");
            exit();
        }

        $errors = $request->validate();

        $userProducts = $this->cartService->getUserProducts();
        $totalSum =$this->cartService->getSum();



        if (empty($errors)) {


            $dto = new OrderCreateDTO(
                $request->getContactName(),
                $request->getContactPhone(),
                $request->getComment(),
                $request->getAddress());

            $this->orderService->createOrder($dto);


        } else {
            require_once '../Views/order_form.php';
        }

        header('Location: /catalog');
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