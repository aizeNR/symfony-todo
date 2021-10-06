<?php

namespace App\Tests\Feature\Controller;

use App\Entity\Task;
use App\Tests\Traits\DatabaseInteractsTrait;
use App\Tests\Traits\WithAuthenticatedClient;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TaskControllerTest extends WebTestCase
{
    use DatabaseInteractsTrait, WithAuthenticatedClient;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @dataProvider validTaskProvider
     */
    public function testTaskCanCreated($title)
    {
        $client = $this->createAuthenticatedClient();
        $router = static::getContainer()->get(UrlGeneratorInterface::class);

        $this->setUpEntityManager();

        $url = $router->generate('v1.tasks.store');

        $client->request('POST', $url, [
            'title' => $title,
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertFindInDatabase(Task::class, ['title' => $title]);
    }

    /**
     * @dataProvider validTaskProvider
     */
    public function testUserCantCreateTaskWithoutAuth($title)
    {
        $client = static::createClient();
        $router = static::getContainer()->get(UrlGeneratorInterface::class);

        $this->setUpEntityManager();

        $url = $router->generate('v1.tasks.store');

        $client->request('POST', $url, [
            'title' => $title,
        ]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertNotFindInDatabase(Task::class, ['title' => $title]);
    }

    /**
     * @dataProvider invalidTaskProvider
     */
    public function testTaskCantCreatedWithInvalidParams($title)
    {
        $client = $this->createAuthenticatedClient();
        $router = static::getContainer()->get(UrlGeneratorInterface::class);

        $this->setUpEntityManager();

        $url = $router->generate('v1.tasks.store');

        $client->request('POST', $url, [
            'title' => $title,
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertNotFindInDatabase(Task::class, ['title' => $title]);
    }

    public function validTaskProvider(): array
    {
        return [
            ['Task 1']
        ];
    }

    public function invalidTaskProvider(): array
    {
        return [
            ['TT'],
            ['To long for task title, brrrrrr']
        ];
    }
}