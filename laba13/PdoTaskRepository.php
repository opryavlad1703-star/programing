<?php
declare(strict_types=1);

require_once __DIR__ . '/TaskStorageInterface.php';
require_once __DIR__ . '/Task.php';

class PdoTaskRepository implements TaskStorageInterface
{
    public function __construct(private PDO $pdo) {}

    public function getByUserId(int $userId, int $page = 1, int $perPage = 5): array
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM tasks 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        return array_map(fn($row) => Task::fromArray($row), $rows);
    }

    public function countByUserId(int $userId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return (int)$stmt->fetchColumn();
    }

    public function save(int $userId, string $title, ?string $deadline = null): Task
    {
        $sql = "INSERT INTO tasks (user_id, title, deadline, created_at) 
                VALUES (:user_id, :title, :deadline, NOW())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id'  => $userId,
            ':title'    => trim($title),
            ':deadline' => $deadline ?: null,
        ]);

        $id = (int)$this->pdo->lastInsertId();
        return new Task($id, $userId, $title, false, $deadline);
    }

    public function toggleStatus(int $id, int $userId): bool
    {
        $sql = "UPDATE tasks 
                SET is_completed = NOT is_completed, updated_at = NOW() 
                WHERE id = :id AND user_id = :user_id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }

    public function delete(int $id, int $userId): bool
    {
        $sql = "DELETE FROM tasks WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}