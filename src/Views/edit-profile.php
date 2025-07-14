<?php

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['userId']))
{
    $userId = $_SESSION['userId'];

    $pdo = new PDO("pgsql:host=postgres_db; port=5432; dbname=mydb", 'ksu', '123');
    $stmt = $pdo->query('SELECT * FROM users WHERE id = ' . $_SESSION['userId']);
    $user = $stmt->fetch();

} else {
    header('Location: /handle_login');
    exit;
}


?>




<form action="/edit-profile" method="POST">
    <div class="container">
        <h1>Редактирование профиля</h1>
        <p>Заполните для обновления данных</p>
        <hr>

        <label for="name"><b>Введите новое имя</b></label>
        <?php if  (isset($error['name'])): ?>
            <?php echo $error['name']; ?>
        <?php endif; ?>
        <input type="text" placeholder= <?php echo $user['name'] ?> name="name" id="name">

        <label for="email"><b>Введите новый Email</b></label>
        <?php if  (isset($error['email'])): ?>
            <?php echo $error['email']; ?>
        <?php endif; ?>
        <input type="text" placeholder= <?php echo $user['email'] ?> name="email" id="email">

        <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
        <button type="submit" class="registerbtn">Register</button>
    </div>

    <div class="container signin">
        <p>Already have an account? <a href="#">Sign in</a>.</p>
    </div>
</form>

<style>
    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
        padding: 16px;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .registerbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
        background-color: #f1f1f1;
        text-align: center;
    }
</style>

