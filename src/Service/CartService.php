<?php

namespace Service;

use Model\UserProducts;
use DTO\UserProductsDTO;
use Service\AuthService;
use Model\Product;
class CartService
{
    private UserProducts $userProducts;
    private AuthService $authService;
    private Product $productsModel;

    public function __construct()
    {
        $this->userProducts = new UserProducts();
        $this->authService = new AuthService();
        $this->productsModel = new Product();

    }

    public function addProduct(UserProductsDTO $data)
    {
        $userId = $this->authService->getCurrentUser();
        $product = $this->userProducts->checkProduct($userId->getId(), $data->getProductId());



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
        $product = $this->userProducts->checkProduct($userId->getId(), $data->getProductId());

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
            $total += $userProduct->getProduct()->getTotalSum();
        }
        return $total;

    }

}