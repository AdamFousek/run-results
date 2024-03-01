<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Commands\UploadedFile\CreateUploadFileResultRow;
use App\Models\Illuminate\UploadFileResultRow;

interface IlluminateUploadFileResultRowRepositoryInterface
{
    public function create(CreateUploadFileResultRow $command): UploadFileResultRow;
}
