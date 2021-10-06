<?php

namespace App\DTO\User;

use App\DTO\BaseDTO;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDTO extends BaseDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=5, max=16)
     * @Assert\Email
     * @var string
     */
    private string $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6, max=16)
     * @var string
     */
    private string $password;

    /**
     * @Assert\Image()
     * @var ?UploadedFile
     */
    private ?UploadedFile $avatar;

    public function __construct(string $email, string $password, ?UploadedFile $avatar = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar;
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

    /**
     * @return ?UploadedFile
     */
    public function getAvatar(): ?UploadedFile
    {
        return $this->avatar;
    }
}