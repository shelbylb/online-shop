<?php

namespace Service;

use DTO\UserProductsDTO;
use Model\Product;
use Model\UserProducts;
use Service\Auth\AuthInterface;
use Service\Auth\AuthSessionService;

class CartService
{
    private UserProducts $userProducts;
    private AuthInterface $authService;
    private Product $productsModel;

    public function __construct()
    {
        $this->userProducts = new UserProducts();
        $this->authService = new AuthSessionService();
        $this->productsModel = new Product();

    }

    public function addProduct(UserProductsDTO $data)
    {
        $userId = $this->authService->getCurrentUser();
        $product = $this->userProducts->checkProduct($data->getProductId(), $data->getProductId());

        if($product){
            $amount = $product->getAmount() + $data->getAmount();
            $this->userProducts->update($userId->getId(), $data->getProductId(), $amount);

        } else{
            $this->userProducts->add($userId->getId(), $data->getProductId(), $data->getAmount());

        }


    }

    public function decreaseProduct(UserProductsDTO $data)
    {
        $userId = $this->authService->getCurrentUser();
        $product = $this->userProducts->checkProduct($data->getProductId(), $data->getProductId());

        if($product) {
            $amount = $product->getAmount() - $data->getAmount();
            $this->userProducts->update($userId->getId(), $data->getProductId(), $amount);
            $this->removeFromCart($userId->getId(), $data->getProductId());
        }


    }

    public function removeFromCart($userId, $productId)
    {
        $product = $this->userProducts->checkProduct($userId, $productId);

        if($product){
            $amount = $product->getAmount();

            if ($amount === 0){
                $this->userProducts->deleteProduct($userId, $productId);

            }
        }

    }

    public function getUserProducts()
    {
        $user = $this->authService->getCurrentUser();

        if($user === null){
            return [];
        }

        $userProducts = $this->userProducts->getAllUserProductsByUserId($user->getId());

        foreach ($userProducts as $userProduct) {
            $product = $this->productsModel->getOneById($userProduct->getProductId());

            $userProduct->setProduct($product);
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
        print_r($total);
        return $total;

    }

}