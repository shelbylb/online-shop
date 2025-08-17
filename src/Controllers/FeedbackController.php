<?php

namespace Controllers;

use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProducts;
use Model\Feedback;

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


    public function addFeedback()

    {

        $errors = $this->validate($_POST);

        if (empty($errors)) {

            $user = $this->authService->getCurrentUser();

            $this->feedback->addFeedback(

                $user->getId(),

                $_POST["product_id"],

                $_POST["score"],

                $_POST["comment"]);
        }
    }

    public function validate()
    {

        $errors = [];

        if (!isset($data['score'])) {

            $errors['score'] = "Поставьте Вашу оценку";

        }

        $user = $this->authService->getCurrentUser();
        $orders = $this->orderModel->getAllByUserId($user->getId());
        $productId = $_SESSION['productId'];

        $flag = false;
        foreach ($orders as $order) {

            $orderId = $order->getOrderId();
            $productFromOrder = $this->orderProductModel->getAllByOrderId($orderId);
            $productIdByOrder = $productFromOrder->getProductId();

            if($productIdByOrder == $productId) {
                $flag = true;
                break;
            }

        }

        if ($flag === false) {
            $errors['productId'] = "Товара нет в Ваших заказах";
        }


        return $errors;
    }



    public function getFeedback(){
        $data = $_POST;
        $feedbacks = $this->feedback->getAllFeedbackByProductId($data['productId']);
        require_once '../Views/feedback.php';
    }


}