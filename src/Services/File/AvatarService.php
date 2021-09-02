<?php

namespace App\Services\File;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarService extends FileService
{
    private string $disk;

    /**
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->disk = $directory;
    }

    public function save(UploadedFile $file, string $fileName = null): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->guessExtension();

        if (!is_null($fileName) && strlen($fileName) > 3) {
            $fileNameWithExtension = "{$fileName}.{$extension}";

            $file->move($this->disk, $fileName);

            return $fileNameWithExtension;
        }

        $uniqid = uniqid();
        $newFileName = "{$originalFilename}-{$uniqid}.{$extension}";

        $file->move($this->disk, $newFileName);

        return $newFileName;
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