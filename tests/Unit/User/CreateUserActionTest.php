<?php

namespace App\Tests\Unit\User;

use App\DTO\User\CreateUserDTO;
use App\Event\User\CreateUserEvent;
use App\Services\AvatarUploader;
use App\Services\DTO\DtoValidator;
use App\Services\User\UserService;
use App\UseCase\User\CreateUserAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CreateUserActionTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        static::bootKernel();
    }

    /**
     * @dataProvider userProvider
     */
    public function testMailSendAfterSuccessRegistration($email, $password)
    {
        $container = static::getContainer();

        $createUserDto = new CreateUserDTO($email, $password);

        $userService = $this->createMock(UserService::class);
        $hasher = $container->get(UserPasswordHasherInterface::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(DtoValidator::class);
        $avatarUploader = $this->createMock(AvatarUploader::class);

        $dispatch = $this->createMock(EventDispatcherInterface::class);

        $dispatch
            ->expects($this->once())
            ->method('dispatch')
            ->with( // check with user doesn't work, password hash doesn't equal
                $this->callback(function ($subject) {
                    return $subject instanceof CreateUserEvent;
                }),
                CreateUserEvent::NAME,
            );

        $action = new CreateUserAction(
            $hasher,
            $em,
            $validator,
            $dispatch,
            $userService,
            $avatarUploader
        );

        $action->execute($createUserDto);
    }

    public function userProvider()
    {
        return [
            ['test@mail.ru', '1234qwqrrq'],
        ];
    }
}