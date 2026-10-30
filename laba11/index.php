<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/TaskRepository.php';

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$repo = new TaskRepository(__DIR__ . '/tasks.json');
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'add') {
            $title = trim($_POST['title'] ?? '');
            $deadline = trim($_POST['deadline'] ?? '') ?: null;
            $repo->add($title, $deadline);
        } elseif ($action === 'complete') {
            $repo->complete((int) ($_POST['id'] ?? 0));
        } elseif ($action === 'delete') {
            $repo->delete((int) ($_POST['id'] ?? 0));
        }
        header('Location: index.php');
        exit;
    } catch (ValidationException $e) {
        $errorMessage = implode(', ', $e->getErrors());
    } catch (TaskNotFoundException $e) {
        $errorMessage = $e->getMessage();
    } catch (Throwable $e) {
        $errorMessage = "Критична помилка: " . $e->getMessage();
    } finally {
        // Код аудиту/логгування
    }
}

$tasks = $repo->findAll();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Лабораторна 11 (ООП + Exceptions)</title>
    <style>
        .error { color: red; font-weight: bold; margin-bottom: 15px; }
        .stats { margin-bottom: 15px; font-weight: bold; }
        .status-Виконано { color: green; }
        .status-Прострочено { color: red; }
        .status-В-процесі { color: orange; }
    </style>
</head>
<body>
    <header>
        <p>Привіт, <?= htmlspecialchars($_SESSION['user_name']) ?>! [<a href="logout.php">Вийти</a>]</p>
    </header>
    <hr>

    <?php if ($errorMessage): ?>
        <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <h2>Додати нове завдання</h2>
    <form action="index.php" method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="title" placeholder="Назва завдання">
        <input type="date" name="deadline">
        <button type="submit">Додати</button>
    </form>

    <h2>Список завдань</h2>
    <div class="stats">
        Всього: <?= count($tasks) ?> | 
        Виконано: <?= $repo->countCompleted() ?> | 
        Прострочено: <?= $repo->countOverdue() ?>
    </div>

    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <strong><?= htmlspecialchars($task->getTitle()) ?></strong> 
                (Створено: <?= $task->getCreatedAt() ?>) — 
                <span class="status-<?= str_replace(' ', '-', $task->getStatusLabel()) ?>">
                    [<?= $task->getStatusLabel() ?>]
                </span>

                <?php if (!$task->isCompleted()): ?>
                    <form action="index.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="complete">
                        <input type="hidden" name="id" value="<?= $task->getId() ?>">
                        <button type="submit">✓</button>
                    </form>
                <?php endif; ?>

                <form action="index.php" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $task->getId() ?>">
                    <button type="submit">X</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>