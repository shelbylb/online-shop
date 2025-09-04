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
    private AuthService $authService;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->userProducts = new UserProducts();
        $this->userModel = new User();
        $this->orderProductModel = new OrderProduct();
        $this->authService = new AuthService();

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
}