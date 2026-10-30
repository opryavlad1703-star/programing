# Звіт з Лабораторної роботи №9

## 1. Фрагмент коду авторизації (login.php)
```php
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === 'admin' && $_POST['password'] === '12345') {$_SESSION['is_logged_in'] = true;
        $_SESSION['user_name'] = 'Адміністратор';
        header('Location: index.php');
        exit;
    }
}
?>
## 2. Обмеження доступу (create.php)

```php
<?php
session_start();

if (!isset($_SESSION['is_logged_in']) \vert{}\vert{}$_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>  