<!DOCTYPE html>
<html>
<head>
    <!-- Здесь размещается «шапка» страницы: мета-информация, стили, скрипты -->
    <meta charset="UTF-8">
    <title>Название страницы</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Другие скрипты и мета-теги -->
</head>
<body>
<div class="container">
    <a href="/profile">Мой профиль</a>
    <a href="/cart">Корзина</a>
    <h3>Catalog</h3>
    <div class="card-deck">
<<<<<<< Updated upstream
        <?php foreach ($products as $product): ?>
            <div class="card text-center">
                <a href="#">
                    <div class="card-header">
                        Hit!
                    </div>
                    <img class="card-img-top" src="<?php echo $product->getImageUrl()?>" alt="Card image">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product->getName();?></p>
                        <a href="#"><h5 class="card-title"><?php echo $product->getDescription();?></h5></a>
                        <div class="card-footer">
                            <?php echo $product->getPrice();?>
=======
        <?php foreach ($products

        as $product): ?>
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
>>>>>>> Stashed changes
                        </div>
                    </form>
                    <a href="#"><h5 class="card-title"><?php echo $product->getDescription(); ?></h5></a>
                    <div class="card-footer">
                        <?php echo $product->getPrice(); ?>

                    </div>
<<<<<<< Updated upstream
                </a>
=======
                </div>
            </a>


            </form>
            <div class="amount">

                <?php
                // Ищем количество для текущего товара по его ID
                $productId = $product->getId();
                $amount = 0;

                foreach ($userProductsAmount as $userProductAmount) {
                    if ($userProductAmount->getProductId() == $productId) {
                        $amount = $userProductAmount->getAmount();
                        break; // Нашли — выходим из цикла
                    }
                }


                ?>
>>>>>>> Stashed changes
            </div>
            <form action="/add-cart" method="POST">
                <div class="container">

                    <input type="hidden" placeholder="Введите артикул" name="productId" value="<?php echo $product->getId()?>" id="productId" required>


<<<<<<< Updated upstream
                    <input type="text" placeholder="Введите количество" name="amount" id="amount" required>

                    <button type="submit" class="registerbtn">Добавить в корзину</button>
                </div>

            </form>
        <?php endforeach; ?>
    </div>
</div>
=======
            <div class="quantity-container">
                <?php if ($amount > 0): ?>
                    <form action="/decrease-cart" method="POST" style="display: inline-flex;">
                        <input type="hidden" name="productId" value="<?php echo $product->getId() ?>" id="productId"
                               required>
                        <input type="hidden" name="amount" id="amount" value="1" min="1">
                    </form>


                    <button type="button" class="btn-minus">-</button>
                    <input type="number" class="quantity-input"
                           value="<?php echo $amount; ?>" min="1" readonly>
                    <button type="button" class="btn-plus">+</button>


                    <form action="/add-cart" method="POST" style="display: inline-flex;">
                        <input type="hidden" name="productId" value="<?php echo $product->getId() ?>" id="productId"
                               required>
                        <input type="hidden" name="amount" id="amount" value="1" min="1">
                    </form>
                <?php else: ?>
                    <form action="/add-cart" method="POST">
                        <input type="hidden" name="productId" value="<?php echo $product->getId() ?>" id="productId"
                               required>
                        <input type="hidden" name="amount" id="amount" value="1" min="1">
                        <button type="submit" class="registerbtn btn-add">Добавить в корзину</button>
                    </form>
                <?php endif; ?>
            </div>

            <?php endforeach; ?>
        </div>

        <script>

            $(document).ready(function () {
                $('.quantity-container').each(function () {
                    const $input = $(this).find('.quantity-input');
                    const $minus = $(this).find('.btn-minus');
                    const $plus = $(this).find('.btn-plus');

                    // Получаем productId из скрытого поля внутри card
                    const productId = $(this).closest('.card')
                        .find('input[name="productId"]').val();

                    $minus.click(function (e) {
                        e.preventDefault();
                        const val = parseInt($input.val());
                        if (val > 1) {
                            const newVal = val - 1;
                            $input.val(newVal);

                            // Отправляем AJAX-запрос на уменьшение
                            updateCartQuantity(productId, newVal);
                        }
                    });

                    $plus.click(function (e) {
                        e.preventDefault();
                        const val = parseInt($input.val());
                        const newVal = val + 1;
                        $input.val(newVal);

                        // Отправляем AJAX-запрос на увеличение
                        updateCartQuantity(productId, newVal);
                    });
                });

                // Функция для отправки изменений на сервер
                function updateCartQuantity(productId, amount) {
                    $.ajax({
                        url: '/update-cart-quantity', // единый endpoint
                        type: 'POST',
                        data: {
                            productId: productId,
                            amount: amount
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                console.log('Количество обновлено:', response);
                                // Можно добавить визуальную обратную связь
                            } else {
                                alert('Ошибка обновления количества: ' + response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Ошибка AJAX:', error);
                            alert('Не удалось обновить количество. Проверьте соединение.');
                        }
                    });
                }
            });
        </script>


        </script>

    </div>
</body>
</html>
>>>>>>> Stashed changes

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

    .card-footer{
        font-weight: bold;
        font-size: 18px;
        background-color: white;
    }
<<<<<<< Updated upstream
=======

    .btn-add {
        padding: 5px 15px;
        font-size: 16px;
        background-color: #4CAF50; /* Зелёный цвет */
        color: white;
        border: none;
        border-radius: 4px;
    }


>>>>>>> Stashed changes
</style>