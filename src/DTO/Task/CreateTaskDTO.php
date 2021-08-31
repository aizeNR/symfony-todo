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

    public function __construct(?string $title, ?string $description)
    {
        $this->title = $title;
        $this->description = $description;
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
}