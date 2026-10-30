<?php
declare(strict_types=1);

class TaskManagerException extends RuntimeException {}

class TaskNotFoundException extends TaskManagerException {}

class ValidationException extends TaskManagerException
{
    public function __construct(private array $errors = [], string $message = "Помилка валідації")
    {
        parent::__construct($message);
    }

    public function getErrors(): array { return $this->errors; }
}