<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/PdoTaskRepository.php';

$pdo = Database::getConnection();
$repository = new PdoTaskRepository($pdo);

$currentUserId = 1; // Використовуємо id = 1 для користувача Vlados
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 5;

// Обробка запитів додавання, зміни статусу та видалення
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create' && !empty($_POST['title'])) {
        $repository->save($currentUserId, $_POST['title'], $_POST['deadline'] ?? null);
    } elseif ($action === 'toggle' && isset($_POST['id'])) {
        $repository->toggleStatus((int)$_POST['id'], $currentUserId);
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $repository->delete((int)$_POST['id'], $currentUserId);
    }

    header('Location: index.php?page=' . $page);
    exit;
}

$tasks = $repository->getByUserId($currentUserId, $page, $perPage);
$totalTasks = $repository->countByUserId($currentUserId);
$totalPages = (int)ceil($totalTasks / $perPage);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Менеджер завдань (MySQL / PDO)</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 650px; margin: 30px auto; padding: 0 15px; }
        .completed { text-decoration: line-through; color: #777; }
        .overdue { color: #d9534f; font-weight: bold; margin-left: 5px; }
        .task-item { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .pagination { margin-top: 20px; display: flex; gap: 5px; }
        .pagination a { padding: 5px 10px; border: 1px solid #ccc; text-decoration: none; color: #333; }
        .pagination a.active { background-color: #007bff; color: white; border-color: #007bff; }
        form.inline-form { display: inline; }
    </style>
</head>
<body>
    <h1>Менеджер завдань (Лабораторна №13)</h1>

    <form method="POST">
        <input type="hidden" name="action" value="create">
        <input type="text" name="title" placeholder="Назва завдання..." required>
        <input type="datetime-local" name="deadline">
        <button type="submit">Додати</button>
    </form>

    <hr>

    <div class="task-list">
        <?php if (empty($tasks)): ?>
            <p>Завдань поки немає.</p>
        <?php endif; ?>

        <?php foreach ($tasks as $task): ?>
            <div class="task-item">
                <div>
                    <span class="<?= $task->isCompleted() ? 'completed' : '' ?>">
                        <?= htmlspecialchars($task->getTitle()) ?>
                    </span>
                    <?php if ($task->isOverdue()): ?>
                        <span class="overdue">(Протерміновано!)</span>
                    <?php endif; ?>
                </div>

                <div>
                    <form method="POST" class="inline-form">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="id" value="<?= $task->id ?>">
                        <button type="submit"><?= $task->isCompleted() ? 'Відновити' : 'Виконано' ?></button>
                    </form>

                    <form method="POST" class="inline-form">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $task->id ?>">
                        <button type="submit">Видалити</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</body>
</html>