<?php

namespace App\Tests\Feature\Controller;

use App\Entity\User;
use App\Services\AvatarUploader;
use App\Tests\Traits\DatabaseInteractsTrait;
use App\Tests\Traits\FileCreatorTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegisteredTest extends WebTestCase
{
    use DatabaseInteractsTrait;
    use FileCreatorTrait;

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

    public function testAvatarCanUpload()
    {
        $uploadedFile = $this->createUploadFile('test.jpg', 'image/jpeg');
        $avatarUploader = static::getContainer()->get(AvatarUploader::class);

        $this->client->request('POST', '/api/v1/register', [
            'email' => 'test@mail.ru',
            'password' => '1234qwert'
        ], [
            'avatar' => $uploadedFile,
        ]);

        $entity = $this->getRepository(User::class)->findOneBy(['email' => 'test@mail.ru']);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNotNull($entity->getAvatar());
        $this->assertFileExists($avatarUploader->getDirectory() . $entity->getAvatar());

        unlink($avatarUploader->getDirectory() . $entity->getAvatar());
    }

    public function testBlankAvatarDoesntUpload()
    {
        $uploadedFile = $this->createUploadFile('test.jpg');

        $this->client->request('POST', '/api/v1/register', [
            'email' => 'test@mail.ru',
            'password' => '1234qwert'
        ], [
            'avatar' => $uploadedFile,
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertNotFindInDatabase(User::class, ['email' => 'test@mail.ru']);
    }

    public function invalidUserProvider(): array
    {
        return [
            ['testmail.ru', '1234Qwert'],
            ['tesasdasdasdasdasdasdt@mail.ru', '1234Qwert'],
            ['test@mail.ru', '1234'],
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

    public function tearDown(): void
    {
        parent::tearDown();

        $this->tearDownFile(); // mb available auto delete?
    }
}