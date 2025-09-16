<?php

namespace Controllers;

use Model\Product;
use Service\CartService;

class ProductController extends BaseController
{
    private Product $productModel;
    private CartService $cartService;

    public function __construct(){
        parent:: __construct();
        $this->productModel = new Product();
        $this->cartService = new CartService();
    }

    public function getCatalog()
    {
        if(isset($_SESSION['userId'])){
            header("Location: /login");
        }

        $products = $this -> productModel -> catalog();
        $userProducts = $this->cartService->getUserProducts();

        require_once '../Views/catalog_page.php';
    }

}