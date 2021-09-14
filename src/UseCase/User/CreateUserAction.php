<?php

namespace App\UseCase\User;

use App\DTO\User\CreateUserDTO;
use App\Entity\User;
use App\Event\User\CreateUserEvent;
use App\Services\AvatarUploader;
use App\Services\DTO\DtoValidator;
use App\Services\MailService;
use App\Services\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

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
     * @var UserService
     */
    private $userService;

    /**
     * @var AvatarUploader
     */
    private AvatarUploader $avatarUploader;
    private EventDispatcherInterface $dispatcher;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface      $entityManager,
        DtoValidator $validator,
        EventDispatcherInterface $dispatcher,
        UserService $userService,
        AvatarUploader $avatarUploader
    )
    {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->userService = $userService;
        $this->avatarUploader = $avatarUploader;
        $this->dispatcher = $dispatcher;
    }

    /**
     */
    public function execute(CreateUserDTO $createUserDTO): User
    {
        $this->validator->validateDTO($createUserDTO);

        $email = $createUserDTO->getEmail();
        $password = $createUserDTO->getPassword();
        $avatar = $createUserDTO->getAvatar();

        $this->userService->checkUserExists($email);

        $path = null;
        if ($avatar) {
            $path = $this->avatarUploader->upload($avatar);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $user->setAvatar($path);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(new CreateUserEvent($user), CreateUserEvent::NAME);

        return $user;
    }
}