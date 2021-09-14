<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Traits\DatabaseInteractsTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegisteredTest extends WebTestCase
{
    use DatabaseInteractsTrait;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->setUpEntityManager();
    }

    /**
     * @dataProvider validUserProvider
     */
    public function testUserCanRegistered($email, $password)
    {
        // TODO change route, use name
        $this->client->request('POST', '/api/v1/register', [
            'email' => $email,
            'password' => $password
        ]);

        $this->assertFindInDatabase(User::class, ['email' => $email]);
    }

    /**
     * @dataProvider invalidUserProvider
     */
    public function testUserCantRegistered($email, $password)
    {
        $this->client->request('POST', '/api/v1/register', [
            'email' => $email,
            'password' => $password
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    /**
     * @dataProvider doesntUniqueEmailProvider
     */
    public function testWhatEmailUniqueForUser($email, $password)
    {
        $this->client->request('POST', '/api/v1/register', [
            'email' => $email,
            'password' => $password
        ]);

        $this->client->request('POST', '/api/v1/register', [
            'email' => $email,
            'password' => $password
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function invalidUserProvider(): array
    {
        return [
            ['testmail.ru', '1234Qwert'],
            ['tesasdasdasdasdasdasdt@mail.ru', '1234Qwert'],
            ['testmail.ru', '1234'],
        ];
    }

    public function validUserProvider(): array
    {
        return [
            ['test@mail.ru', '1234qwer'],
            ['test3213@mail.ru', '1234qwasder']
        ];
    }

    public function doesntUniqueEmailProvider(): array
    {
        return [
            ['test@mail.ru', '1234qwer'],
        ];
    }
}