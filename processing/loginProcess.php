<?php
session_start();
include '../incs/db.php';


$identifier = htmlspecialchars(trim($_POST['identifier']),    ENT_QUOTES, 'UTF-8');
$password   = htmlspecialchars(trim($_POST['password']),      ENT_QUOTES, 'UTF-8');


// Определение, что ввел пользователь: email или номер телефона
if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
    // Это email
    $mysql    = $link->query("SELECT * FROM `users` WHERE `email` = '$identifier'")->fetch_assoc();
    $hash = $mysql['password'];
    if (password_verify($password, $hash)) {
        $_SESSION['user_id'] = $mysql['id'];
        // 3. Перенаправление на страницу личного кабинета
        header("Location: ../papers/profile.php");
        exit;
    } else {
        $_SESSION['message'] = "Неверный пароль";
        header('location: ../papers/login.php');
        exit();
    }
} elseif (preg_match('/^[0-9]{10,15}$/', $identifier)) {
    // Это номер телефона
    $mysql    = $link->query("SELECT * FROM `users` WHERE `phone` = '$identifier'")->fetch_assoc();
    $hash = $mysql['password'];
    if (password_verify($password, $hash)) {
        $_SESSION['user_id'] = $mysql['id'];
        // 3. Перенаправление на страницу личного кабинета
        header("Location: ../papers/profile.php");
        exit;
    } else {
        $_SESSION['message'] = "Неверный пароль";
        header('location: ../papers/login.php');
        exit();
    }
} else {
    $_SESSION['message'] = "Введите корректный email или номер телефона.";
    header('location: ../papers/login.php');
    exit();
}



