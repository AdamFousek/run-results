<?php

declare(strict_types=1);


namespace App\Commands\UploadedFile;

use App\Models\Illuminate\UploadFileResultRow;
use App\Repositories\IlluminateUploadFileResultRowRepositoryInterface;

readonly class CreateUploadFileResultRowHandler
{
    public function __construct(
        private IlluminateUploadFileResultRowRepositoryInterface $repository,
    ) {
    }

    public function handle(CreateUploadFileResultRow $command): UploadFileResultRow
    {
        return $this->repository->create($command);
    }
}
