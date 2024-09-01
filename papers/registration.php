<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Регистрация</title>
</head>
<body>
<div class="container">
    <h1>Регистрация</h1>
    <form action="../processing/registrationProcess.php" method="POST">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" required>

<!--        <label for="phone">Телефон:</label>-->
<!--        <input type="text" id="phone" name="phone" required>-->
<!---->
<!--        <label for="email">Почта:</label>-->
<!--        <input type="email" id="email" name="email" required>-->

        <label for="identifier">Email или номер телефона:</label>
        <input type="text" id="identifier" name="identifier" required>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>

        <label for="password_repeat">Повторите пароль:</label>
        <input type="password" id="password_repeat" name="password_repeat" required>

        <input type="submit" value="Зарегистрироваться">
    </form>
    <a href="login.php">Уже есть аккаунт? Войти</a>
    <?php
    session_start();
    if ($_SESSION['message']) {
        echo '<p id="err">'.$_SESSION['message'].'</p>';
    }
    unset($_SESSION['message']);
    ?>
</div>
</body>
</html>