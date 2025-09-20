<!DOCTYPE html>
<html lang="ru">
<a href="/profile">Мой профиль</a>
<a href="/catalog">Каталог</a>
<a href="/orders">Мои заказы</a>
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

    <!-- Блок существующих отзывов -->
    <div class="reviews-block">
        <h3>Отзывы (<?php echo count($feedbacks); ?>)</h3>
        <?php if (count($feedbacks) > 0): ?>
            <?php foreach ($feedbacks as $review): ?>
                <div class="review-item">
                    <div class="review-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?php if ($i <= $review->getScore()): ?>active<?php endif; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <p class="review-text"><?php echo $review->getComment(); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Отзывов пока нет</p>
        <?php endif; ?>
    </div>

    <!-- Форма для отзыва -->
    <div class="review-form">
        <h3>Оставить отзыв</h3>
        <form action="/add-feedback" method="post">
            <!-- Блок рейтинга -->
            <div class="score-container">
                <input type="radio" id="star1" name="score" value="1">
                <label for="star1">&#9733;</label>
                <input type="radio" id="star2" name="score" value="2">
                <label for="star2">&#9733;</label>
                <input type="radio" id="star3" name="score" value="3">
                <label for="star3">&#9733;</label>
                <input type="radio" id="star4" name="score" value="4">
                <label for="star4">&#9733;</label>
                <input type="radio" id="star5" name="score" value="5">
                <label for="star5">&#9733;</label>
            </div>

            <!-- Поле для текста отзыва -->
            <input type="hidden" name="productId" value="<?php echo $product->getId()?>" id="productId" required>
            <textarea name="comment" placeholder="Напишите ваш отзыв..." required></textarea>
            <button type="submit">Отправить</button>
        </form>
    </div>
</div>

<style>
    /* Существующие стили */
    
    .reviews-block {
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .review-item {
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .review-rating {
        margin-bottom: 10px;
    }

    .star {
        font-size: 18px;
        color: #ccc;
        cursor: pointer;
    }

    .star.active {
        color: orange;
    }

    .review-text {
        font-size: 16px;
        color: #333;
    }
</style>
</body>
</html>
