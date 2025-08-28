<?php

namespace Service;
use DTO\OrderCreateDTO;
use Model\Order;
use Model\UserProducts;
use Model\User;
use Model\OrderProduct;

class OrderService
{
    private Order $orderModel;
    private UserProducts $userProducts;

    private User $userModel;
    private OrderProduct $orderProductModel;

    public function ___construct()
    {
        $this->orderModel = new Order();
        $this->userProducts = new UserProducts();
        $this->userModel = new User();
        $this->orderProductModel = new OrderProduct();

    }

    public function createOrder(OrderCreateDTO $data)
    //public function createOrder(string $contactName, string $contactPhone, string $comment, string $address, int $userId)
    {
        print_r($data);


        //$orderId = $this->orderModel->create($contactName, $contactPhone, $comment, $address, $userId);
        $orderId = $this->orderModel->create(
            $data->getContactName(),
            $data->getContactPhone(),
            $data->getComment(),
            $data->getAddress(),
            $data->getUserId()->getId());

        //$userProducts = $this->userProducts->getAllUserProductsByUserId($userId);
        $userProducts = $this->userProducts->getAllUserProductsByUserId($data->getUserId()->getId());


            foreach ($userProducts as $userProduct) {
                print_r($userProduct);
                $productId = $userProduct->getProductId();  // нет перехода
                $amount = $userProduct->getAmount();

                $this->orderProductModel->create($orderId, $productId, $amount);
            }

            //удаляет товары из корзины
            $this->userProducts->deleteByUserId($data->getUserId()->getId());

    }
}