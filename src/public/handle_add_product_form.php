<?php

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['userId'])){
    header("Location: /login_form.php");
    exit();
}

function validateAddCart(array  $data): array
{
    $errors = [];

    $productId = (int)$data['productId'];

    if(isset($productId)){



            $pdo = new PDO("pgsql:host=postgres_db; port=5432; dbname=mydb", 'ksu', '123');
            $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :productId');
            $stmt->execute(['productId' => $productId]);
            $product = $stmt->fetch();
            if($product === false){
                $errors['productId'] = 'товар не найден';
            }

    } else {
        $errors['product_id'] = 'укажите артикул';
    }

    if(isset($data['amount'])){
        $amount = (int)$data['amount'];

        if($amount < 0 && $amount > 100){
            $errors['amount'] = 'Введите колличество товара от 1 до 100';
        }
    }

    return $errors;
}

$errors = validate($_POST);

if(empty($errors)){
    $pdo = new PDO("pgsql:host=postgres_db; port=5432; dbname=mydb", 'ksu', '123');
    $userId = $_SESSION['userId'];
    $productId = $_POST['productId'];
    $amount = $_POST['amount'];

    $stmt  = $pdo->prepare("SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId");
    $stmt->execute(['productId' => $productId, 'userId' => $userId]);
    $data = $stmt->fetch();

    if($data === false) {

        $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, amount) VALUES (:userId, :productId, :amount)");
        $stmt->execute(['userId' => $_SESSION['userId'], 'productId' => $productId, 'amount' => $amount]);

    } else {
        $amount = $data['amount'] + $amount;
        $stmt = $pdo->prepare("UPDATE user_products SET amount = :amount WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute(['amount' => $amount, 'userId' => $userId, 'productId' => $productId]);
    }
}