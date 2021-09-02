<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class AvatarUploader
{
    private string $directory;
    private SluggerInterface $slugger;

    /**
     * @param string $directory
     * @param SluggerInterface $slugger
     */
    public function __construct(string $directory, SluggerInterface $slugger)
    {
        $this->directory = $directory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, string $fileName = null): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->guessExtension();

        if (!is_null($fileName) && strlen($fileName) > 3) {
            $safeFilename = $this->slugger->slug($fileName);

            $fileNameWithExtension = "{$safeFilename}.{$extension}";

            $file->move($this->directory, $fileName);

            return $fileNameWithExtension;
        }

        $uniqid = uniqid();
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFileName = "{$safeFilename}-{$uniqid}.{$extension}";

        $file->move($this->directory, $newFileName);

        return $newFileName;
    }
}