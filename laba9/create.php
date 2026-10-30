<?php
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Створення завдання</title>
</head>
<body>
    <h2>Створення нового завдання</h2>
    <p>Ласкаво просимо, <?= htmlspecialchars($_SESSION['user_name']) ?>! Ця сторінка доступна лише для авторизованих користувачів.</p>
    <a href="index.php">На головну</a>
</body>
</html>