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
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var User
     */
    private $user;

    public function __construct(string $title, ?string $description, User $user)
    {
        $this->title = $title;
        $this->description = $description;
        $this->user = $user;
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
}