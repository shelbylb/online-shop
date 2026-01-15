<div class="container">
    <a href="/profile">Мой профиль</a>
    <a href="/catalog">Каталог</a>
    <a href="/orders">Мои заказы</a>
    <h3>Корзина</h3>
    <div class="card-deck">
        <?php foreach ($userProducts as $product): ?>

            <div class="card text-center">
                <a href="#">

                    <img class="card-img-top" src="<?php echo $product->getProduct()->getImageUrl()?>" alt="Card image">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product->getProduct()->getName();?></p>
                        <a href="#"><h5 class="card-title"><?php echo $product->getProduct()->getDescription();?></h5></a>
                        <div class="card-footer">
                            <?php echo $product->getProduct()->getPrice();?>
                        </div>
                    </div>
                </a>
            </div>

            <div class="quantity-container">

                    <form action="/decrease-cart" method="POST" style="display: inline-flex;">
                        <input type="hidden" name="productId" value="<?php echo $product->getProductId()?>" id="productId" required>
                        <input type="hidden" placeholder="1" name="amount" id="amount" value="1" min="1">
                        <button type="submit" class="registerbtn btn-minus">-</button>
                    </form>

                    <input
                            type="number"
                            class="quantity-input"
                            placeholder="<?php echo $product->getAmount(); ?>"
                            value="<?php echo $product->getAmount(); ?>"
                            min="1"
                            readonly
                            style="width: 40px; text-align: center;"
                    >

                    <form action="/add-cart" method="POST" style="display: inline-flex;">
                        <input type="hidden" name="productId" value="<?php echo $product->getProductId()?>" id="productId" required>
                        <input type="hidden" placeholder="1" name="amount" id="amount" value="1" min="1">
                        <button type="submit" class="registerbtn btn-plus">+</button>
                    </form>
            </div>

        <?php endforeach; ?>

        <form action="/create-order" method="POST">
            <button type="submit" class="registerbtn btn-add">Оформить заказ</button>
        </form>
    </div>
</div>

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
        max-width: 200px;    /* Ограничение максимальной ширины */
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

    .btn-add {
        padding: 5px 15px;
        font-size: 16px;
        background-color: #4CAF50; /* Зелёный цвет */
        color: white;
        border: none;
        border-radius: 4px;
    }
</style>
