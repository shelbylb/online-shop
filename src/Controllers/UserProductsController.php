<?php

namespace Controllers;

use Model\UserProducts;
use Request\UserProductsRequest;
use Service\AuthService;
use Service\CartService;
use DTO\UserProductsDTO;

class UserProductsController extends BaseController
{
    private UserProducts $UserProductsModel;
    private CartService $cartService;

    public function __construct()
    {
        parent:: __construct();
        $this->UserProductsModel = new UserProducts();
        $this->cartService = new CartService();
    }

    public function addCart(UserProductsRequest $request)
    {
        if ($this->authService->check()) {

            $errors = $request->validate();
            $user = $this->authService->getCurrentUser();

            if (empty($errors)) {



                $dto = new UserProductsDTO(
                    $request->getProductId(),
                    $request->getAmount(),
                    $user
                );

                $this->cartService->addProduct($dto);


            }

            header("Location: /catalog");
        } else {
            header("Location: /login");
            exit();
        }
    }

    public function decreaseCart(UserProductsRequest $request)
    {
        if ($this->authService->check()) {

            $errors = $request->validate();
            $user = $this->authService->getCurrentUser();

            if (empty($errors)) {
                $dto = new UserProductsDTO(
                    $request->getProductId(),
                    $request->getAmount(),
                    $user
                );

                $this->cartService->decreaseProduct($dto);


            }

            header("Location: /catalog");
        } else {
            header("Location: /login");
            exit();
        }
    }



    public function getCart()
    {

        if ($this->authService->check()) {


            $user = $this->authService->getCurrentUser();


            $userProducts = $this->UserProductsModel->getAllUserProductsByUserId($user->getId());

            $productsCart = [];

            foreach ($userProducts as $userProduct) {
                $productId = $userProduct->getProductId();
                $product = $userProduct->getById($productId);
                $product->setAmount($userProduct->getAmount());
                $productsCart[] = $product;

            }
            require_once '../Views/cart.php';
        } else {
            header("Location: /login");
            exit();
        }

    }

}