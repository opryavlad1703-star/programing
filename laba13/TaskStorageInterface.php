<?php
declare(strict_types=1);

interface TaskStorageInterface
{
    /** @return Task[] */
    public function getByUserId(int $userId, int $page = 1, int $perPage = 5): array;

    public function countByUserId(int $userId): int;

    public function save(int $userId, string $title, ?string $deadline = null): Task;

    public function toggleStatus(int $id, int $userId): bool;

    public function delete(int $id, int $userId): bool;
}