<?php

declare(strict_types=1);


namespace App\Commands\UploadedFile;

readonly class CreateUploadedFile
{
    public function __construct(
        public string $path,
        public string $name,
        public bool $isPublic,
        public int $filableId,
        public string $filableType,
    ) {
    }
}
