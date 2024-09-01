<?php
session_start();
require '../incs/db.php';
// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Подключение к базе данных


// Получаем текущие данные пользователя из базы данных
$user_id = $_SESSION['user_id'];
$mysql = $link->query("SELECT * FROM `users` WHERE `id` = '$user_id'")->fetch_assoc();


?>




<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Личный кабинет</title>
    <style>
    </style>
</head>
<body>
<div class="profile-container">
    <h2>Личный кабинет</h2>
    <form action="../processing/profileProcess.php" method="POST">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name">
        <br><br>

        <label for="identifier">Email или номер телефона:</label>
        <input type="text" id="identifier" name="identifier">
        <br><br>

        <label for="password">Новый пароль:</label>
        <input type="password" id="password" name="password">
        <br><br>

        <label for="confirm_password">Подтвердите новый пароль:</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <br><br>

        <input type="submit" value="Сохранить изменения">
    </form>
    <?php
    if ($_SESSION['message']) {
        echo '<p id="err">'.$_SESSION['message'].'</p>';
    }
    unset($_SESSION['message']);
    ?>
</div>
</body>
</html>