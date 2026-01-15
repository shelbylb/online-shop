<?php

namespace Controllers;

use Model\Product;
use Service\CartService;

class ProductController extends BaseController
{
    private CartService $cartService;

    public function __construct(){
        parent:: __construct();
        $this->cartService = new CartService();
    }

    public function getCatalog()
    {
        if(isset($_SESSION['userId'])){
            header("Location: /login");
        }

        $products = Product::catalog();
        $userProductsAmount = $this->cartService->getUserProducts();

        require_once '../Views/catalog_page.php';
    }

}