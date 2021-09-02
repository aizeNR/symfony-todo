<?php

namespace App\Services\File;

use Symfony\Component\HttpFoundation\File\File;

class AvatarService extends FileService
{
    private string $disk;

    /**
     * @param string $disk
     */
    public function __construct(string $disk)
    {
        $this->disk = $disk;
    }

    // TODO fix file name, fix path
    public function save(File $file, string $fileName = null): string
    {
        $extension = $file->guessExtension();
        $originalFileName = $file->getFilename();

        if (!is_null($fileName)) {
            return $file->move($this->disk, "{$fileName}.{$extension}")->getPathname();
        }

        return $file->move($this->disk, "{$originalFileName}.{$extension}")->getPathname();
    }

    public function get(string $path): File
    {
        // TODO: Implement get() method.
    }

    public function delete(string $path): bool
    {
        // TODO: Implement delete() method.
    }
}