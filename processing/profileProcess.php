<?php
//session_start();
//require '../incs/db.php';
//
//// Проверка авторизации
//if (!isset($_SESSION['user_id'])) {
//    header("Location: login.php");
//    exit;
//}
//
//$user_id = $_SESSION['user_id'];
//
//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//    $name = trim($_POST['name']);
//    $identifier = trim($_POST['identifier']);
//    $new_password = $_POST['password'];
//    $confirm_password = $_POST['confirm_password'];
//
//    // Определение, что ввел пользователь: email или телефон
//    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
//        $email = $identifier;
//        $phone = null;
//    } elseif (preg_match('/^[0-9]{10,15}$/', $identifier)) {
//        $phone = $identifier;
//        $email = null;
//    } else {
//        $_SESSION['message'] = "Введите корректный email или номер телефона.";
//        header('location: ../papers/profile.php');
//        exit;
//    }
//
//    // Проверка на уникальность email или телефона
//    $stmt = $link->prepare("SELECT `id` FROM `users` WHERE (`email` = ? OR `phone` = ?) AND `id` != ?");
//    $stmt->bind_param("ssi", $email, $phone, $user_id);
//    $stmt->execute();
//    $result = $stmt->get_result();
//
//    if ($result->num_rows > 0) {
//        $_SESSION['message'] = "Пользователь с таким email или номером телефона уже существует.";
//        header('location: ../papers/profile.php');
//        exit;
//    }
//
//    // Если пароль изменен, проверяем его и хэшируем
//    if (!empty($new_password)) {
//        if ($new_password !== $confirm_password) {
//            $_SESSION['message'] = "Пароли не совпадают.";
//            header('location: ../papers/profile.php');
//            exit;
//        }
//
//        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
//        $password_clause = ", `password` = ?";
//    } else {
//        $hashed_password = null;
//        $password_clause = "";
//    }
//
//    // Обновление данных пользователя в базе
//    $query = "UPDATE `users` SET `name` = ?, `email` = ?, `phone` = ? $password_clause, `updated_at` = NOW() WHERE `id` = ?";
//    $stmt = $link->prepare($query);
//
//    if (!empty($hashed_password)) {
//        $stmt->bind_param("ssssi", $name, $email, $phone, $hashed_password, $user_id);
//    } else {
//        $stmt->bind_param("sssi", $name, $email, $phone, $user_id);
//    }
//
//    if ($stmt->execute()) {
//        $_SESSION['message'] = "Данные успешно обновлены";
//        header('location: ../papers/profile.php');
//        exit();
//    } else {
//        $_SESSION['message'] = "Ошибка обновления данных.";
//        header('location: ../papers/profile.php');
//        exit();
//    }
//
//    $stmt->close();
//    header('location: ../papers/profile.php');
//    exit();
//}



session_start();
require '../incs/db.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $identifier = trim($_POST['identifier']);
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $email = null;
    $phone = null;

    // Определение, что ввел пользователь: email, телефон или оставил поле пустым
    if (!empty($identifier)) {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $email = $identifier;
        } elseif (preg_match('/^[0-9]{10,15}$/', $identifier)) {
            $phone = $identifier;
        } else {
            $_SESSION['message'] = "Введите корректный email или номер телефона.";
            header('location: ../papers/profile.php');
            exit;
        }

        // Проверка на уникальность email или телефона
        $stmt = $link->prepare("SELECT `id` FROM `users` WHERE (`email` = ? OR `phone` = ?) AND `id` != ?");
        $stmt->bind_param("ssi", $email, $phone, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['message'] = "Пользователь с таким email или номером телефона уже существует.";
            header('location: ../papers/profile.php');
            exit;
        }
    }

    // Формирование SQL-запроса
    $query = "UPDATE `users` SET `name` = ?";
    $types = "s";
    $params = [$name];

    // Обновляем email или телефон, в зависимости от введенного пользователем значения
    if ($email !== null) {
        $query .= ", `email` = ?";
        $types .= "s";
        $params[] = $email;
    } elseif ($phone !== null) {
        $query .= ", `phone` = ?";
        $types .= "s";
        $params[] = $phone;
    }

    // Если пароль изменен, проверяем его и добавляем в запрос
    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $_SESSION['message'] = "Пароли не совпадают.";
            header('location: ../papers/profile.php');
            exit;
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query .= ", `password` = ?";
        $types .= "s";
        $params[] = $hashed_password;
    }

    // Завершаем запрос
    $query .= ", `updated_at` = NOW() WHERE `id` = ?";
    $types .= "i";
    $params[] = $user_id;

    // Подготовка и выполнение запроса
    $stmt = $link->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Данные успешно обновлены";
    } else {
        $_SESSION['message'] = "Ошибка обновления данных.";
    }

    $stmt->close();
    header('location: ../papers/profile.php');
    exit();
}
