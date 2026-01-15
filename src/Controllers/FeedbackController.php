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
    public function __construct()
    {
        parent:: __construct();

    }


    public function addFeedback(AddFeedbackRequest $request)

    {

        $errors = $request->validate();

        if (empty($errors))
        {

            $user = $this->authService->getCurrentUser();

            Feedback::addFeedback(

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
        $feedbacks = Feedback::getAllFeedbackByProductId($productId);
        $product = Product::getOneById($productId);
        require_once '../Views/feedback.php';
    }


}