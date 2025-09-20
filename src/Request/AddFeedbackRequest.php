<?php

namespace Request;
use Model\Order;
use Model\OrderProduct;
use Service\Auth\AuthSessionService;

class AddFeedbackRequest
{
    private Order $orderModel;
    private AuthSessionService $authService;
    private OrderProduct $orderProductModel;
    public function __construct(private array $data)
    {
        $this->orderModel = new Order();
        $this->authService = new AuthSessionService();
        $this->orderProductModel = new OrderProduct();

    }

    public function getProductId(): int
    {
        return $this->data["productId"];
    }

    public function getScore(): string
    {
        return $this->data["score"];
    }

    public function getComment(): string
    {
        return $this->data["comment"];
    }

    public function validate()
    {

        $errors = [];

        if (!isset($this->data['score'])) {

            $errors['rating'] = "Поставьте Вашу оценку";

        }

        $user = $this->authService->getCurrentUser();
        $orders = $this->orderModel->getAllByUserId($user->getId());

        $productId = $this->data['productId'];

        $flag = false;
        foreach ($orders as $order) {

            $orderId = $order->getId();
            $productsByOrderId = $this->orderProductModel->getAllByOrderId($orderId);

            foreach ($productsByOrderId as $productOrder) {
                $productIdByOrder = $productOrder->getProductId();

                if ($productIdByOrder == $productId) {
                    $flag = true;
                    break;
                }
            }

        }

        if ($flag === false) {
            $errors['productId'] = "Товара нет в Ваших заказах";
        }

        return $errors;
    }

}