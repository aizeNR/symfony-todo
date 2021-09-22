<?php

namespace App\DTO\Task;

class TaskFilterDTO
{
    private ?string $taskTitle;
    private ?int $taskStatus;

    public function __construct(array $filter)
    {
        $this->taskTitle = $filter['taskTitle'] ?? null;
        $this->taskStatus = $filter['taskStatus'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getTaskTitle(): ?string
    {
        return $this->taskTitle;
    }

    /**
     * @return int|null
     */
    public function getTaskStatus(): ?int
    {
        return $this->taskStatus;
    }
}