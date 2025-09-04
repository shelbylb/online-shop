<div class="container">
    <a href="/profile">Мой профиль</a>
    <a href="/cart">Корзина</a>
    <a href="/login">Войти</a>
    <a href="/registration">Зарегистрироваться</a>
    <h3>Catalog</h3>
    <div class="card-deck">
        <?php foreach ($products as $product): ?>
            <div class="card text-center">
                <a href="#">
                    <div class="card-header">
                        Hit!
                    </div>
                    <img class="card-img-top" src="<?php echo $product->getImageUrl()?>" alt="Card image">
                    <div class="card-body">
                        <form action="/feedback" method="POST">
                            <div class="container">

                                <input type="hidden" name="productId" value="<?php echo $product->getId() ?>" id="productId">

                                <button type="submit" class="registerbtn"><?php echo $product->getName(); ?></button>
                            </div>
                        </form>
                        <a href="#"><h5 class="card-title"><?php echo $product->getDescription();?></h5></a>
                        <div class="card-footer">
                            <?php echo $product->getPrice();?>
                        </div>
                    </div>
                </a>
            </div>
            <form action="/add-cart" method="POST">
                <div class="container">

                    <input type="hidden" name="productId" value="<?php echo $product->getId()?>" id="productId" required>


                    <input type="number" placeholder="1" name="amount" id="amount" value="1" min="1">


                    <button type="submit" class="registerbtn">+</button>
                </div>

            </form>
            <form action="/decrease-cart" method="POST">
                <div class="container">

                    <input type="hidden" name="productId" value="<?php echo $product->getId()?>" id="productId" required>


                    <input type="number" placeholder="1" name="amount" id="amount" value="1" min="1">


                    <button type="submit" class="registerbtn">-</button>
                </div>

            </form>
        <?php endforeach; ?>
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
</style>