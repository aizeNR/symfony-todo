<?php

namespace App\DTO\Task;

use App\DTO\BaseDTO;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTaskDTO extends BaseDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3, max=12)
     * @var string
     */
    private string $title;

    /**
     * @Assert\Length(min=5, max=255)
     * @var string|null
     */
    private ?string $description;

    /**
     * @var User
     */
    private User $user;

    /**
     * @Assert\Choice(callback={"App\Entity\Dictionary\TaskStatusDictionary", "getFlipDictionary"})
     * @var int
     */
    private int $status;

    private array $tagIds;

    public function __construct(string $title, ?string $description, User $user, int $status = 0, $tagIds)
    {
        $this->title = $title;
        $this->description = $description;
        $this->user = $user;
        $this->status = $status;
        $this->tagIds = $tagIds;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getTagIds(): array
    {
        return $this->tagIds;
    }
}