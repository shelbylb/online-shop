<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Название страницы</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <a href="/profile">Мой профиль</a>
    <a href="/cart">Корзина</a>
    <a href="/login">Войти</a>
    <a href="/registration">Зарегистрироваться</a>
    <h3>Каталог</h3>
    <div class="card-deck">
        <?php foreach ($products as $product): ?>
            <?php
            // Ищем количество для текущего товара по его ID
            $productId = $product->getId();
            $amount = 0;
            foreach ($userProductsAmount as $userProductAmount) {
                if ($userProductAmount->getProductId() == $productId) {
                    $amount = $userProductAmount->getAmount();
                    break;
                }
            }
            ?>

            <div class="card text-center">
                <a href="#">
                    <div class="card-header">Hit!</div>
                    <img class="card-img-top" src="<?php echo $product->getImageUrl() ?>" alt="Card image">
                    <div class="card-body">
                        <form action="/feedback" method="POST">
                            <div class="container">
                                <input type="hidden" name="productId" value="<?php echo $product->getId() ?>">
                                <button type="submit" class="registerbtn"><?php echo $product->getName(); ?></button>
                            </div>
                        </form>
                        <a href="#"><h5 class="card-title"><?php echo $product->getDescription(); ?></h5></a>
                        <div class="card-footer"><?php echo $product->getPrice().' руб.'; ?></div>
                    </div>
                </a>

                <div class="quantity-container" data-product-id="<?php echo $product->getId(); ?>">
                    <?php if ($amount > 0): ?>
                        <!-- Если количество больше 0 — показываем кнопки + и - и поле ввода -->
                        <form class="decrease-form" method="POST" style="display: inline-flex;">
                            <input type="hidden" name="productId" value="<?php echo $product->getId()?>" required>
                            <input type="hidden" name="amount" value="1" min="1">
                            <button type="submit" class="registerbtn btn-minus">-</button>
                        </form>

                        <input
                            type="number"
                            class="quantity-input"
                            value="<?php echo $amount; ?>"
                            min="0"
                            readonly
                            style="width: 40px; text-align: center;"
                        >

                        <form class="increase-form-plus" method="POST" style="display: inline-flex;">
                            <input type="hidden" name="productId" value="<?php echo $product->getId()?>" required>
                            <input type="hidden" name="amount" value="1" min="1">
                            <button type="submit">+</button>
                        </form>
                    <?php else: ?>
                        <!-- Если количество равно 0 — показываем кнопку "Добавить в корзину" -->
                        <form class="add-to-cart-form" method="POST">
                            <input type="hidden" name="productId" value="<?php echo $product->getId()?>" required>
                            <input type="hidden" name="amount" value="1" min="1">
                            <button type="submit" class="registerbtn btn-add">Добавить в корзину</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        // Обработчик для кнопки "+"
        $('.increase-form-plus').submit(function(event) {
            event.preventDefault();

            var form = $(this);
            var productId = form.find('input[name="productId"]').val();
            var quantityContainer = form.closest('.quantity-container');
            var quantityInput = quantityContainer.find('.quantity-input');

            console.log('Увеличиваем количество для товара:', productId);

            $.ajax({
                type: "POST",
                url: "/add-cart",
                data: form.serialize(),
                success: function(response){
                    // Увеличиваем значение в поле ввода
                    var currentValue = parseInt(quantityInput.val());
                    quantityInput.val(currentValue + 1);
                    console.log('Количество обновлено:', currentValue + 1);
                },
                error: function(xhr, status, error) {
                    console.error('Ошибка при увеличении количества:', error);
                    alert('Произошла ошибка при обновлении количества');
                }
            });
        });

        // Обработчик для кнопки "-"
        $('.decrease-form').submit(function(event) {
            event.preventDefault();

            var form = $(this);
            var productId = form.find('input[name="productId"]').val();
            var quantityContainer = form.closest('.quantity-container');
            var quantityInput = quantityContainer.find('.quantity-input');

            console.log('Уменьшаем количество для товара:', productId);

            $.ajax({
                type: "POST",
                url: "/decrease-cart",
                data: form.serialize(),
                success: function(response){
                    var currentValue = parseInt(quantityInput.val());
                    var newValue = currentValue - 1;

                    if (newValue <= 0) {
                        // Если количество стало 0, показываем кнопку "Добавить в корзину"
                        quantityContainer.html(`
                        <form class="add-to-cart-form" method="POST">
                            <input type="hidden" name="productId" value="${productId}" required>
                            <input type="hidden" name="amount" value="1" min="1">
                            <button type="submit" class="registerbtn btn-add">Добавить в корзину</button>
                        </form>
                    `);
                        // Перепривязываем обработчик для новой кнопки
                        bindAddToCartHandler();
                    } else {
                        quantityInput.val(newValue);
                    }
                    console.log('Количество обновлено:', newValue);
                },
                error: function(xhr, status, error) {
                    console.error('Ошибка при уменьшении количества:', error);
                    alert('Произошла ошибка при обновлении количества');
                }
            });
        });

        // Обработчик для кнопки "Добавить в корзину"
        function bindAddToCartHandler() {
            $('.add-to-cart-form').off('submit').on('submit', function(event) {
                event.preventDefault();

                var form = $(this);
                var productId = form.find('input[name="productId"]').val();
                var quantityContainer = form.closest('.quantity-container');

                console.log('Добавляем товар в корзину:', productId);

                $.ajax({
                    type: "POST",
                    url: "/add-cart",
                    data: form.serialize(),
                    success: function(response){
                        // Заменяем кнопку на блок с количеством
                        quantityContainer.html(`
                        <form class="decrease-form" method="POST" style="display: inline-flex;">
                            <input type="hidden" name="productId" value="${productId}" required>
                            <input type="hidden" name="amount" value="1" min="1">
                            <button type="submit" class="registerbtn btn-minus">-</button>
                        </form>
                        <input type="number" class="quantity-input" value="1" min="0" readonly style="width: 40px; text-align: center;">
                        <form class="increase-form-plus" method="POST" style="display: inline-flex;">
                            <input type="hidden" name="productId" value="${productId}" required>
                            <input type="hidden" name="amount" value="1" min="1">
                            <button type="submit">+</button>
                        </form>
                    `);

                        // Перепривязываем обработчики для новых кнопок
                        bindEventHandlers();
                        console.log('Товар добавлен в корзину');
                    },
                    error: function(xhr, status, error) {
                        console.error('Ошибка при добавлении товара:', error);
                        alert('Произошла ошибка при добавлении товара в корзину');
                    }
                });
            });
        }

        // Функция для перепривязки всех обработчиков
        function bindEventHandlers() {
            $('.increase-form-plus').off('submit').on('submit', function(event) {
                event.preventDefault();
                // ... код обработчика +
            });

            $('.decrease-form').off('submit').on('submit', function(event) {
                event.preventDefault();
                // ... код обработчика -
            });

            bindAddToCartHandler();
        }

        // Инициализация обработчиков при загрузке страницы
        bindAddToCartHandler();
    });
</script>

<style>
    body {
        font-style: sans-serif;
    }
    a {
        text-decoration: none;
    }
    a:hover {
        text-decoration: none;
    }
    h3 {
        line-height: 3em;
    }
    .card {
        max-width: 16rem;
    }
    .card-img-top {
        width: 100%;
        height: auto;
        max-width: 200px;
    }
    .card:hover {
        box-shadow: 1px 2px 10px lightgray;
        transition: 0.2s;
    }
    .card-header {
        font-size: 13px;
        color: gray;
        background-color: white;
    }
    .card-footer {
        font-weight: bold;
        font-size: 18px;
        background-color: white;
    }
    .btn-add {
        padding: 5px 15px;
        font-size: 16px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
    }
</style>
</html>
