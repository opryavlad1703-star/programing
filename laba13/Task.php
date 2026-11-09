<?php
declare(strict_types=1);

require_once __DIR__ . '/TimestampableTrait.php';

class Task
{
    use TimestampableTrait;

    public function __construct(
        public readonly int    $id,
        public readonly int    $userId,
        private string         $title,
        private bool           $isCompleted = false,
        private ?string        $deadline    = null,
    ) {
        if (empty(trim($title))) {
            throw new InvalidArgumentException('Назва завдання не може бути порожньою.');
        }
    }

    public function getTitle(): string { return $this->title; }
    public function isCompleted(): bool { return $this->isCompleted; }
    public function getDeadline(): ?string { return $this->deadline; }

    public function isOverdue(): bool
    {
        if ($this->isCompleted || $this->deadline === null) {
            return false;
        }
        return strtotime($this->deadline) < time();
    }

    public static function fromArray(array $data): self
    {
        $task = new self(
            id: (int)$data['id'],
            userId: (int)$data['user_id'],
            title: $data['title'],
            isCompleted: (bool)$data['is_completed'],
            deadline: $data['deadline'] ?? null
        );

        if (!empty($data['created_at'])) {
            $task->setCreatedAt(new DateTimeImmutable($data['created_at']));
        }
        if (!empty($data['updated_at'])) {
            $task->setUpdatedAt(new DateTimeImmutable($data['updated_at']));
        }

        return $task;
    }
}