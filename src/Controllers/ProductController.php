<?php

namespace Controllers;

use Model\Product;

class ProductController extends BaseController
{
    private Product $productModel;

    public function __construct(){
        parent:: __construct();
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