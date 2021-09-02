<?php

namespace App\Services\File;

use Symfony\Component\HttpFoundation\File\File;

abstract class FileService
{
    abstract public function save(File $file, ?string $fileName = null): string;
    abstract public function get(string $path): File;
    abstract public function delete(string $path): bool;
}