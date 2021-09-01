<?php

namespace App\Services;

use App\DTO\User\CreateUserDTO;
use App\UseCase\User\CreateUserAction;
use DomainException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserService
{
    /**
     * @var CreateUserAction
     */
    private $createUserAction;

    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(CreateUserAction $createUserAction, MailService $mailService)
    {
        $this->createUserAction = $createUserAction;
        $this->mailService = $mailService;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function createUser(CreateUserDTO $createUserDTO)
    {
        $user = $this->createUserAction->execute($createUserDTO);

        if (!$user) {
            throw new DomainException();
        }

        $this->mailService->sendEmailToUser($user, 'test');
    }
}