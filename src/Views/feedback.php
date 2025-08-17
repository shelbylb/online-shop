<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карточка товара</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="product-card">
    <!-- Блок с изображением товара -->
    <div class="product-image">
        <img src="<?php echo $product->getImageUrl()?>" alt="Изображение товара">
    </div>

    <!-- Блок с информацией о товаре -->
    <div class="product-info">
        <h2 class="product-title"><?php echo $product->getName();?></h2>
        <div class="product-price"><?php echo $product->getPrice();?></div>
        <p class="product-description"><?php echo $product->getDescription();?></p>
    </div>

    <!-- Форма для отзыва -->
    <div class="review-form">
        <h3>Оставить отзыв</h3>
        <form action="#" method="post">
            <!-- Блок рейтинга -->
            <div class="rating-container">
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4">&#9733;</label>
                <input type="radio" id="star5" name="rating" value="5">
                <label for="star5">&#9733;</label>
            </div>

            <!-- Поле для текста отзыва -->
            <textarea name="comment" placeholder="Напишите ваш отзыв..." required></textarea>
            <button type="submit">Отправить</button>
        </form>
    </div>
</div>
</body>
</html>



<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        color: #333;
    }

    .product-card {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .product-image {
        text-align: center;
        margin-bottom: 20px;
    }

    .product-image img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .product-info {
        margin-bottom: 30px;
    }

    .product-title {
        font-size: 24px;
        margin: 0 0 10px;
    }

    .product-price {
        font-size: 22px;
        color: #ff6347;
        margin-bottom: 10px;
    }

    .review-form {
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .rating-container {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .rating-container input {
        display: none;
    }

    .rating-container label {
        font-size: 24px;
        color: #ccc;
        cursor: pointer;
        margin-right: 5px;
    }

    .rating-container label:hover,
    .rating-container label:hover ~ label {
        color: orange;
    }

    .rating-container input:checked ~ label {
        color: orange;
    }

    textarea {
        width: 100%;
        height: 150px
</style>
