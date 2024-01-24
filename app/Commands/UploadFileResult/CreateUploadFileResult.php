<?php

declare(strict_types=1);


namespace App\Commands\UploadFileResult;

use App\Models\Race;

readonly class CreateUploadFileResult
{
    public function __construct(
        public Race $race,
        public string $file,
    ) {
    }
}
