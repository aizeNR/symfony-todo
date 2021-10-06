<?php

namespace App\Tests\Traits;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait WithAuthenticatedClient
{
    protected function createAuthenticatedClient($email = 'test@mail.ru', $password = '123456')
    {
        $client = static::createClient();

        $this->createUser($email, $password);

        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => $email,
                'password' => $password,
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    private function createUser($email, $password)
    {
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $em = static::getContainer()->get(EntityManagerInterface::class);

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($hasher->hashPassword($user, $password));

        $em->persist($user);
        $em->flush();
    }
}