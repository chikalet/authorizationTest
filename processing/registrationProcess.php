<?php
session_start();
include '../incs/db.php';

// Получаем данные из формы и очищаем их
$identifier      = htmlspecialchars(trim($_POST['identifier']),      ENT_QUOTES, 'UTF-8');
$name            = htmlspecialchars(trim($_POST['name']),            ENT_QUOTES, 'UTF-8');
$password        = htmlspecialchars(trim($_POST['password']),        ENT_QUOTES, 'UTF-8');
$password_repeat = htmlspecialchars(trim($_POST['password_repeat']), ENT_QUOTES, 'UTF-8');

// Проверка, что введено: email или телефон
if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
    // Это email
    $email = $identifier;
    $phone = null;
} elseif (preg_match('/^[0-9]{10,15}$/', $identifier)) {
    // Это номер телефона
    $phone = $identifier;
    $email = null;
} else {
    $_SESSION['message'] = "Введите корректный email или номер телефона.";
    header('location: ../papers/registration.php');
    exit();
}

// Если email или телефон пусты, заменяем их на пустую строку
$email = $email ?? ''; // Если $email = NULL, присваиваем пустую строку
$phone = $phone ?? ''; // Если $phone = NULL, присваиваем пустую строку

// Выполняем запрос к базе данных
$query = "SELECT * FROM `users` WHERE `email` = ? OR `phone` = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("ss", $email, $phone);
$stmt->execute();
$result = $stmt->get_result();

// Проверяем, есть ли совпадения в базе
if ($result->num_rows > 0) {
    $_SESSION['message'] = "Набранная почта или телефон уже используются";
    header('location: ../papers/registration.php');
    exit();
} else if (!preg_match("/^[a-zA-Zа-яА-ЯёЁ\s]+$/u", $name)) {
    $_SESSION['message'] = "Имя может содержать только буквы и пробелы.";
    header('location: ../papers/registration.php');
    exit();
} else if ($password != $password_repeat) {
    $_SESSION['message'] = "Пароли не совпадают";
    header('location: ../papers/registration.php');
    exit();
} else {
    // Хэшируем пароль
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Вставляем нового пользователя в базу данных
    $insert_query = "INSERT INTO `users` (`email`, `phone`, `name`, `password`) VALUES (?, ?, ?, ?)";
    $insert_stmt = $link->prepare($insert_query);
    $insert_stmt->bind_param("ssss", $email, $phone, $name, $hashed_password);

    if ($insert_stmt->execute()) {
        $_SESSION['message'] = "Вы зарегистрированы";
        header('location: ../papers/login.php');
    } else {
        $_SESSION['message'] = "Ошибка при регистрации. Попробуйте позже.";
        header('location: ../papers/registration.php');
    }
    exit();
}
