<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUsers() as $user) {
            $user = $this->createUser($user['email'], $user['password']);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createUser(string $email, string $password): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword($user, $password));

        return $user;
    }

    private function getUsers(): array
    {
        return [
            ['email' => 'test@mail.ru', 'password' => 'qwerty'],
            ['email' => 'test1@mail.ru', 'password' => 'qwerty'],
            ['email' => 'test2@mail.ru', 'password' => 'qwerty']
        ];
    }
}
