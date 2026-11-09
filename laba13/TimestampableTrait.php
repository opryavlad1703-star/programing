<?php
declare(strict_types=1);

trait TimestampableTrait
{
    private ?DateTimeImmutable $createdAt = null;
    private ?DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(DateTimeImmutable $date): void { $this->createdAt = $date; }

    public function getUpdatedAt(): ?DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(DateTimeImmutable $date): void { $this->updatedAt = $date; }
}