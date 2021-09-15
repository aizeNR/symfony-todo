<?php

namespace App\Tests\Unit;

use App\Services\AvatarUploader;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarUploaderTest extends KernelTestCase
{
    /**
     * @var UploadedFile
     */
    private UploadedFile $file;

    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->file = $this->createUploadFile();
    }

    private function createUploadFile(): UploadedFile
    {
        $file = tempnam(dirname(__FILE__), 'test');

        if (!$file) {
            throw new LogicException('File doesnt create!');
        }

        return new UploadedFile(
            $file,
            'test.jpg',
            'image/jpeg',
            null,
            true
        );
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

        if (file_exists($this->file->getPathname())) {
            unlink($this->file->getPathname());
        }
    }
}