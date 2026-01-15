<?php

namespace Service;
use DTO\OrderCreateDTO;
use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProducts;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;
use Service\Log\LogDBService;

class OrderService
{
    private AuthInterface $authService;
    private CartService $cartService;
    private LogDBService $log;

    public function __construct()
    {
        $this->authService = new AuthSessionService();
        $this->cartService = new CartService();
        $this->log = new Log\LogDBService();

    }


    /**
     * @throws \Throwable
     */
    public function createOrder(OrderCreateDTO $data)

    {
        try {

            $user = $this->authService->getCurrentUser();

            $userProducts = UserProducts::getAllUserProductsByUserId($user->getId());

            $sum = $this->cartService->getSum();

            if ($sum < 10) {
                throw new \Exception('Для оформления заказа сумма заказа должна быть больше 10 рублей');
            }

            $orderId = Order::create(
                $data->getContactName(),
                $data->getContactPhone(),
                $data->getComment(),
                $data->getAddress(),
                $user->getId());

            foreach ($userProducts as $userProduct) {
                $productId = $userProduct->getProductId();
                $amount = $userProduct->getAmount();

                OrderProduct::create($orderId, $productId, $amount);
            }

            //удаляет товары из корзины
            UserProducts::deleteByUserId($user->getId());

        } catch (\Throwable $exception){

            $data = 'ошибка при создании заказа ';

            $this->log->log($exception, $data);

            throw $exception;

        }

    }


    public function getAll(): array
    {
        $user = $this->authService->getCurrentUser();
        $orders = Order::getAllByUserId($user->getId());

        foreach ($orders as $order) {
            $orderSum = 0; // Сумма для текущего заказа

            // Проходим по всем продуктам в заказе
            foreach ($order->getOrderProducts() as $orderProduct) {
                $itemSum = $orderProduct->getAmount() * $orderProduct->getProduct()->getPrice();
                $orderSum += $itemSum;
                $orderProduct->setItemSum($itemSum);
            }

            // Устанавливаем сумму для заказа
            $order->setSum($orderSum);

        }

        return $orders;
    }

    public function getAl():array
    {
        $user = $this->authService->getCurrentUser();

        $orders = Order::getAllByUserId($user->getId());

        foreach ($orders as $userOrder) {
            $orderProducts = OrderProduct::getAllByOrderId($userOrder->getId());

            $totalSum = 0;
            foreach ($orderProducts as $orderProduct) {
                $product = Product::getOneById($orderProduct->getProductId());
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