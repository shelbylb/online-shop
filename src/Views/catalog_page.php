<!DOCTYPE html>
<html>
<head>
    <!-- Здесь размещается «шапка» страницы: мета-информация, стили, скрипты -->
    <meta charset="UTF-8">
    <title>Название страницы</title>
    <link rel="stylesheet" href="styles.css">

    <!-- Другие скрипты и мета-теги -->
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
            <div class="card text-center">
                <a href="#">
                    <div class="card-header">
                        Hit!
                    </div>
                    <img class="card-img-top" src="<?php echo $product->getImageUrl() ?>" alt="Card image">
                    <div class="card-body">
                        <form action="/feedback" method="POST">
                            <div class="container">

                                <input type="hidden" name="productId" value="<?php echo $product->getId() ?>"
                                       id="productId">

                                <button type="submit" class="registerbtn"><?php echo $product->getName(); ?></button>
                            </div>
                        </form>
                        <a href="#"><h5 class="card-title"><?php echo $product->getDescription(); ?></h5></a>
                        <div class="card-footer">
                            <?php echo $product->getPrice().' руб.'; ?>

                        </div>
                    </div>
                </a>



                <div

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
            </div>


            <div class="quantity-container">
                <?php if ($amount > 0): ?>
                    <!-- Если количество больше 0 — показываем кнопки + и - и поле ввода -->
                    <form action="/decrease-cart" method="POST" style="display: inline-flex;">
                        <input type="hidden" name="productId" value="<?php echo $product->getId()?>" id="productId" required>
                        <input type="hidden" placeholder="1" name="amount" id="amount" value="1" min="1">
                        <button type="submit" class="registerbtn btn-minus">-</button>
                    </form>


                        <input
                                type="number"
                                class="quantity-input"
                                placeholder="<?php echo $amount; ?>"
                                value="<?php echo $amount; ?>"
                                min="1"
                                readonly
                                style="width: 40px; text-align: center;"
                        >


                    <form class="increase-form-plus" onsubmit="return false" method="POST" style="display: inline-flex;">
                        <input type="hidden" name="productId" value="<?php echo $product->getId()?>" id="productId" required>
                        <input type="hidden" placeholder="1" name="amount" id="amount" value="1" min="1">
                        <button type="submit" >+</button>
                    </form>
                <?php else: ?>
                    <!-- Если количество равно 0 — показываем кнопку "Добавить в корзину" -->
                    <form action="/add-cart" method="POST">
                        <input type="hidden" name="productId" value="<?php echo $product->getId()?>" id="productId" required>
                        <input type="hidden" placeholder="1" name="amount" id="amount" value="1" min="1">
                        <button type="submit" class="regis terbtn btn-add">Добавить в корзину</button>
                    </form>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>
    </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>

    $(document).ready(function() {
        var form = $('.increase-form-plus');
        form.submit(function() {
            console.log('hello');
            var form = $(this);

            $.ajax({
                type: "POST",
                url: "/add-cart",
                data: $(this).serialize(),
                success: function(){
                    // Находим конкретное поле ввода для этого товара
                    var quantityInput = form.closest('.quantity-container').find('.quantity-input');
                    var currentValue = parseInt(quantityInput.val()) || 0;
                    quantityInput.val(currentValue + 1);

                    // Также обновляем placeholder
                    quantityInput.attr('placeholder', currentValue + 1);
                }
            })
        });
    });





</script>



</html>


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
        max-width: 200px; /* Ограничение максимальной ширины */
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

    .text-muted {
        font-size: 11px;
    }

    .card-footer {
        font-weight: bold;
        font-size: 18px;
        background-color: white;
    }

    .btn-add {
        padding: 5px 15px;
        font-size: 16px;
        background-color: #4CAF50; /* Зелёный цвет */
        color: white;
        border: none;
        border-radius: 4px;
    }



</style>