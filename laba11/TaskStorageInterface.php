<?php
declare(strict_types=1);

require_once __DIR__ . '/Task.php';

interface TaskStorageInterface
{
    /**
     * @return Task[]
     */
    public function findAll(): array;

    public function findById(int $id): ?Task;

    /**
     * @throws ValidationException
     */
    public function add(string $title, ?string $deadline = null): Task;

    /**
     * @throws TaskNotFoundException
     */
    public function complete(int $id): bool;

    /**
     * @throws TaskNotFoundException
     */
    public function delete(int $id): bool;

    public function countCompleted(): int;

    public function countOverdue(): int;
}