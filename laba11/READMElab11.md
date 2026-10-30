# Звіт з Лабораторної роботи №11

## 1. Код інтерфейсу TaskStorageInterface
```php
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
```
## 2. Код трейта TimestampableTrait

```php
<?php
declare(strict_types=1);

trait TimestampableTrait
{
    private string $createdAt;
    private ?string $updatedAt = null;

    public function initTimestamps(?string $createdAt = null, ?string $updatedAt = null): void
    {
        $this->createdAt =$createdAt ?? date('Y-m-d H:i:s');
        $this->updatedAt =$updatedAt;
    }

    public function touch(): void
    {
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    public function getCreatedAt(): string { return $this->createdAt; }
    public function getUpdatedAt(): ?string { return $this->updatedAt; }
}