<?php

namespace App\DTO\User;

use App\DTO\BaseDTO;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDTO extends BaseDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=5, max=16)
     * @Assert\Email
     * @var string
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6, max=16)
     * @var string
     */
    private $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}