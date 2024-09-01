<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Авторизация</title>
</head>
<body>
<div class="container">
    <h1>Войти</h1>
    <form action="../processing/loginProcess.php" method="POST">

        <label for="identifier">Email или номер телефона:</label>
        <input type="text" id="identifier" name="identifier" required>


        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>

        <!-- Здесь предполагается подключение Yandex SmartCaptcha -->
        <input type="submit" value="Войти">
    </form>
    <a href="registration.php">Нет аккаунта? Зарегистрироваться</a>
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