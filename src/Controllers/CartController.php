<?php

namespace Controllers;

use Request\DecreaseCartRequest;
use Request\AddCartRequest;
use Service\CartService;
use DTO\UserProductsDTO;

class CartController extends BaseController
{
    private CartService $cartService;

    public function __construct()
    {
        parent:: __construct();
        $this->cartService = new CartService();
    }

    public function addCart(AddCartRequest $request)
    {
        if ($this->authService->check()) {

            $errors = $request->validate();

            if (empty($errors)) {

                $dto = new UserProductsDTO(
                    $request->getProductId(),
                    $request->getAmount(),
                );

                $this->cartService->addProduct($dto);
            }

            header("Location: /catalog");
        } else {
            header("Location: /login");
            exit();
        }
    }

    public function decreaseCart(DecreaseCartRequest $request)
    {
        if ($this->authService->check()) {
            $errors = $request->validate();

            if (empty($errors)) {
                $dto = new UserProductsDTO(
                    $request->getProductId(),
                    $request->getAmount()
                );

                $this->cartService->decreaseProduct($dto);
                header("Location: /cart");
            }
        } else {
            header("Location: /login");
            exit();
        }
    }



    public function getCart()
    {

        if ($this->authService->check()) {

            $userProducts = $this->cartService->getUserProducts();

            require_once '../Views/cart.php';
        } else {
            header("Location: /login");
            exit();
        }

    }

}