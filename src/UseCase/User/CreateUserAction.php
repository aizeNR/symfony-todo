<?php

namespace App\UseCase\User;

use App\DTO\User\CreateUserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\DTO\DtoValidator;
use App\Services\MailService;
use App\Services\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
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

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface      $entityManager,
        DtoValidator $validator,
        MailService $mailService,
        UserService $userService
    )
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->mailService = $mailService;
        $this->userService = $userService;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function execute(CreateUserDTO $createUserDTO): User
    {
        $this->validator->validateDTO($createUserDTO);

        $email = $createUserDTO->getEmail();
        $password = $createUserDTO->getPassword();

        $this->userService->checkUserExists($email);

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->mailService->sendEmailToUser($user, 'test');

        return $user;
    }
}