<?php

namespace Service;

use DTO\UserProductsDTO;
use Model\UserProducts;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class CartService
{
    private AuthInterface $authService;

    public function __construct()
    {
        $this->authService = new AuthSessionService();
    }

    public function addProduct(UserProductsDTO $data)
    {
        $userId = $this->authService->getCurrentUser();
        $product = UserProducts::checkProduct($userId->getId(), $data->getProductId());

        if($product){
            $amount = $product->getAmount() + $data->getAmount();
            UserProducts::update($userId->getId(), $data->getProductId(), $amount);

        } else{
            UserProducts::add($userId->getId(), $data->getProductId(), $data->getAmount());

        }

        return $amount;

    }

    public function decreaseProduct(UserProductsDTO $data)
    {
        $userId = $this->authService->getCurrentUser();
        $product = UserProducts::checkProduct($userId->getId(), $data->getProductId());

        if($product) {
            $amount = $product->getAmount() - $data->getAmount();
            UserProducts::update($userId->getId(), $data->getProductId(), $amount);
            $this->removeFromCart($userId->getId(), $data->getProductId());
        }
        return $amount;


    }

    public function removeFromCart($userId, $productId)
    {
        $product = UserProducts::checkProduct($userId, $productId);

        if($product){
            $amount = $product->getAmount();

            if ($amount === 0){
                UserProducts::deleteProduct($userId, $productId);

            }
        }

    }

    public function getUserProducts()
    {
        $user = $this->authService->getCurrentUser();

        if($user === null){
            return [];
        }

        $userProducts = UserProducts::getAllByUserIdWithProducts($user->getId());

        foreach ($userProducts as $userProduct) {
            $totalSum = $userProduct->getAmount() * $userProduct->getProduct()->getPrice();
            $userProduct->setTotalSum($totalSum);

        }

        return $userProducts;

    }

    public function getSum()
    {
        $total = 0;
        foreach ($this->getUserProducts() as $userProduct){
            $total += $userProduct->getTotalSum();
        }
        return $total;
    }

    public function updateQuantity(int $productId, int $amount): array
    {
        // Проверка входных данных
        if ($amount < 1) {
            return [
                'success' => false,
                'message' => 'Количество должно быть не менее 1'
            ];
        }

        $userId = $this->authService->getCurrentUser()->getId();

        try {
            // Проверяем, есть ли товар в корзине
            $product = UserProducts::checkProduct($userId, $productId);

            if (!$product) {
                return [
                    'success' => false,
                    'message' => 'Товар не найден в корзине'
                ];
            }

            // Обновляем количество
            UserProducts::update($userId, $productId, $amount);

            return [
                'success' => true,
                'message' => 'Количество обновлено',
                'productId' => $productId,
                'amount' => $amount
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Ошибка сервера: ' . $e->getMessage()
            ];
        }
    }

}