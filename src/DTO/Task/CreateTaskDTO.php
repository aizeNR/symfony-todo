<?php

namespace App\DTO\Task;

class CreateTaskDTO
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var int
     */
    private $userId;

    public function __construct(string $title, ?string $description, int $userId)
    {
        $this->title = $title;
        $this->description = $description;
        $this->userId = $userId;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}