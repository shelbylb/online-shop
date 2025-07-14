<?php

namespace Controllers;

use Model\Product;

class ProductController
{
    private Product $productModel;

    public function __construct(){
        $this->productModel = new Product();
    }

    public function getCatalog()
    {
        if(isset($_SESSION['userId'])){
            header("Location: /login");
        }

        $products = $this -> productModel -> catalog();


        require_once '../Views/catalog_page.php';
    }

}