<?php

namespace App\UseCase\User;

use App\DTO\User\CreateUserDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserAction
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface      $entityManager,
        ValidatorInterface $validator
    )
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function execute(CreateUserDTO $createUserDTO): User
    {
        $email = $createUserDTO->getEmail();
        $password = $createUserDTO->getPassword();

        // TODO validate
        $this->validateDTO($createUserDTO);

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function validateDTO(CreateUserDTO $createUserDTO)
    {
        $errors = $this->validator->validate($createUserDTO);

        if (count($errors) > 0) { // find a way, to handle it, and reform
            throw new \DomainException($errors);
        }
    }
}