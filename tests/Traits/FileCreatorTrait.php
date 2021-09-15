<?php

namespace App\Tests\Traits;

use LogicException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait FileCreatorTrait
{
    public ?UploadedFile $file = null;

    private function createUploadFile($originalName, $mimeType = null): UploadedFile
    {
        $file = tempnam(dirname(__FILE__), 'test');

        if (!$file) {
            throw new LogicException('File doesnt create!');
        }

        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg(imagecreatetruecolor(10, 10), $file);
                break;
            case 'image/png':
                imagepng(imagecreatetruecolor(10, 10), $file);
                break;
            default:
                // blank file
        }

        $this->file = new UploadedFile(
            $file,
            $originalName,
            $mimeType,
            null,
            true
        );

        return $this->file;
    }

    public function tearDownFile()
    {
        if (!is_null($this->file) && file_exists($this->file->getPathname())) {
            unlink($this->file->getPathname());
        }
    }
}