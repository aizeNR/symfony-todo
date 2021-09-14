<?php

namespace App\Services\User;

use App\Repository\UserRepository;

class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function checkUserExists($email)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if ($user) {
            throw new \DomainException('User already exists!', 422);
        }
    }
}