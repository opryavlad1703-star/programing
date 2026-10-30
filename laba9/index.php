<?php
session_start();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Головна сторінка</title>
</head>
<body>
    <header>
        <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true): ?>
            <p>Привіт, <?= htmlspecialchars($_SESSION['user_name']) ?>! [<a href="logout.php">Вийти</a>]</p>
        <?php else: ?>
            <p>Ви увійшли як Гість. [<a href="login.php">Увійти</a>]</p>
        <?php endif; ?>
    </header>

    <hr>

    <main>
        <h1>Головна сторінка</h1>
        <p><a href="create.php">Перейти до створення завдання (Захищена сторінка)</a></p>
    </main>
</body>
</html>