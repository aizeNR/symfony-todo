<?php

namespace App\Tests\Unit;

use App\Services\AvatarUploader;
use App\Tests\Traits\FileCreatorTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AvatarUploaderTest extends KernelTestCase
{
    use FileCreatorTrait;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->createUploadFile('test.jpg', 'image/jpeg');
    }

    public function testCanSaveImage()
    {
        $container = static::getContainer();
        $avatarUploader = $container->get(AvatarUploader::class);

        $fileName = $avatarUploader->upload($this->file);

        $fullPath = $avatarUploader->getDirectory() . $fileName;

        $this->assertFileExists($fullPath);

        unlink($fullPath);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->tearDownFile(); // mb available auto delete?
    }
}