<?php

namespace Service;

use Model\UserProducts;

class CartService
{
    private UserProducts $userProducts;

    public function __construct()
    {
        $this->userProducts = new UserProducts();

    }

    public function addProduct(int $productId, int $amount, int $userId)
    {
        $product = $this->userProducts->checkProduct($userId, $productId);
        

        if($product){
            $amount = $product->getAmount() + $amount;
            $this->userProducts->update($userId, $productId, $amount);
        } else{
            $this->userProducts->add($userId, $productId, $amount);
        }


    }

    public function decreaseProduct(int $productId, int $userId, int $amount)
    {
        $product = $this->userProducts->checkProduct($userId, $productId);

        if($product){
            $amount = $product->getAmount() - $amount;
            $this->userProducts->update($userId, $productId, $amount);
        } else{
            $this->userProducts->deleteByUserId($userId, $productId, $amount);
        }


    }

}