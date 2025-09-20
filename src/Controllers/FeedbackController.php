<?php

namespace Controllers;

use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProducts;
use Model\Feedback;
use Request\AddFeedbackRequest;
use Request\GetFeedbackRequest;

class FeedbackController extends BaseController
{
    private Order $orderModel;
    private UserProducts $userProduct;
    private OrderProduct $orderProductModel;
    private Product $productModel;
    private Feedback $feedback;


    public function __construct()
    {
        parent:: __construct();
        $this->orderModel = new Order();
        $this->userProduct = new UserProducts();
        $this->orderProductModel = new OrderProduct();
        $this->productModel = new Product();
        $this->feedback = new Feedback();


    }


    public function addFeedback(AddFeedbackRequest $request)

    {

        $errors = $request->validate();

        if (empty($errors))
        {

            $user = $this->authService->getCurrentUser();

            $this->feedback->addFeedback(

                $user->getId(),
                $request->getProductId(),
                $request->getComment(),
                $request->getScore()
            );
            header("Location: /catalog");
        } else {
            print_r($errors);
        }

    }





    public function getFeedback(GetFeedbackRequest $request){
        $productId = $request->getProductId();
        $feedbacks = $this->feedback->getAllFeedbackByProductId($productId);
        $product = $this -> productModel -> getOneById($productId);
        require_once '../Views/feedback.php';
    }


}