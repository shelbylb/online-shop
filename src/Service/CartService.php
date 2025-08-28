<?php

namespace Service;

use Model\UserProducts;
use DTO\UserProductsDTO;
class CartService
{
    private UserProducts $userProducts;

    public function __construct()
    {
        $this->userProducts = new UserProducts();

    }

    public function addProduct(UserProductsDTO $data)
    {
        $product = $this->userProducts->checkProduct($data->getUserId()->getId(), $data->getProductId());



        if($product){
            $amount = $product->getAmount() + $data->getAmount();
            $this->userProducts->update($data->getUserId()->getId(), $data->getProductId(), $amount);

        } else{
            $this->userProducts->add($data->getUserId()->getId(), $data->getProductId(), $data->getAmount());

        }


    }

    public function decreaseProduct(UserProductsDTO $data)
    {
        $product = $this->userProducts->checkProduct($data->getUserId()->getId(), $data->getProductId());

        if($product) {
            $amount = $product->getAmount() - $data->getAmount();
            $this->userProducts->update($data->getUserId()->getId(), $data->getProductId(), $amount);
            $this->removeFromCart($data->getUserId()->getId(), $data->getProductId());
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

}