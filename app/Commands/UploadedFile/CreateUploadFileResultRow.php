<?php

declare(strict_types=1);


namespace App\Commands\UploadedFile;

class CreateUploadFileResultRow
{
    public function __construct(
        public int $uplodFileId,
        public int $row,
        public string $data,
        public string $error,
    ) {
    }
}
