<?php

namespace Service;
use Model\Order;
use Model\UserProducts;
use Model\User;

class OrderService
{
    private Order $orderModel;
    private UserProducts $userProducts;

    private User $userModel;

    public function ___construct()
    {
        $this->orderModel = new Order();
        $this->userProducts = new UserProducts();
        $this->userModel = new User();

    }

    public function createOrder(string $contactName, string $contactPhone, string $comment, string $address, int $userId)
    {


        $orderId = $this->orderModel->create($contactName, $contactPhone, $comment, $address, $userId);

        $userProducts = $this->userProducts->getAllUserProductsByUserId($userId);


            foreach ($userProducts as $userProduct) {
                $productId = $userProduct->getProductId();  // нет перехода
                $amount = $userProduct->getAmount();

                $this->orderProductModel->create($orderId, $productId, $amount);
            }

            //удаляет товары из корзины
            $this->userProducts->deleteByUserId($userId);

    }
}