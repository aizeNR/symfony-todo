<?php

namespace App\Services\File;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class FileService
{
    abstract public function save(UploadedFile $file, ?string $fileName = null): string;
    abstract public function get(string $path): File;
    abstract public function delete(string $path): bool;
}