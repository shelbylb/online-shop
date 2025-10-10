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
    private Order $orderModel;
    private UserProducts $userProducts;

    private OrderProduct $orderProductModel;
    private AuthInterface $authService;

    private Product $productModel;
    private CartService $cartService;
    private LogDBService $log;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->userProducts = new UserProducts();
        $this->orderProductModel = new OrderProduct();
        $this->authService = new AuthSessionService();
        $this->productModel = new Product();
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

            $userProducts = $this->userProducts->getAllUserProductsByUserId($user->getId());

            $sum = $this->cartService->getSum();

            if ($sum < 1000) {
                throw new \Exception('Для оформления заказа сумма заказа должна быть больше 1000 рублей');
            }

            $orderId = $this->orderModel->create(
                $data->getContactName(),
                $data->getContactPhone(),
                $data->getComment(),
                $data->getAddress(),
                $user->getId());

            foreach ($userProducts as $userProduct) {
                $productId = $userProduct->getProductId();
                $amount = $userProduct->getAmount();

                $this->orderProductModel->create($orderId, $productId, $amount);
            }

            //удаляет товары из корзины
            $this->userProducts->deleteByUserId($user->getId());

        } catch (\Throwable $exception){

            $data = 'ошибка при создании заказа ';

            $this->log->log($exception, $data);

            throw $exception;

        }

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