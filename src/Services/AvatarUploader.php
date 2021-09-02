<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AvatarUploader
{
    private string $directory;

    /**
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    public function upload(UploadedFile $file, string $fileName = null): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->guessExtension();

        if (!is_null($fileName) && strlen($fileName) > 3) {
            $fileNameWithExtension = "{$fileName}.{$extension}";

            $file->move($this->directory, $fileName);

            return $fileNameWithExtension;
        }

        $uniqid = uniqid();
        $newFileName = "{$originalFilename}-{$uniqid}.{$extension}";

        $file->move($this->directory, $newFileName);

        return $newFileName;
    }
}