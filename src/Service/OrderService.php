<?php

namespace Service;
use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProducts;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class OrderService
{
    private Order $orderModel;
    private UserProducts $userProducts;

    private OrderProduct $orderProductModel;
    private AuthInterface $authService;

    private Product $productModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->userProducts = new UserProducts();
        $this->orderProductModel = new OrderProduct();
        $this->authService = new AuthSessionService();
        $this->productModel = new Product();

    }

    public function createOrder(OrderCreateDTO $data)

    {
        $user = $this->authService->getCurrentUser();

        $orderId = $this->orderModel->create(
            $data->getContactName(),
            $data->getContactPhone(),
            $data->getComment(),
            $data->getAddress(),
            $user->getId());


        $userProducts = $this->userProducts->getAllUserProductsByUserId($user->getId());


        foreach ($userProducts as $userProduct) {
                $productId = $userProduct->getProductId();  // нет перехода
                $amount = $userProduct->getAmount();

                $this->orderProductModel->create($orderId, $productId, $amount);
            }

            //удаляет товары из корзины
            $this->userProducts->deleteByUserId($user->getId());

    }

    public function getAll():array
    {
        $user = $this->authService->getCurrentUser();

        $orders = $this->orderModel->getAllByUserId($user->getId());

        foreach ($orders as $userOrder) {
            $orderProducts = $this->orderProductModel->getAllByOrderId($userOrder->getId());

            $totalSum = 0;
            foreach ($orderProducts as $orderProduct) {
                $product = $this->productModel->getOneById($orderProduct->getProductId());
                $orderProduct->setProduct($product);
                $itemSum = $orderProduct->getAmount() * $product->getPrice();
                $orderProduct->setSum($itemSum);

                $totalSum += $itemSum;
            }

            $userOrder->setOrderProducts($orderProducts);
            $userOrder->setSum($totalSum);

        }

        return $orders;


    }
}