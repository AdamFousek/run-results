<?php

declare(strict_types=1);


namespace App\Commands\TopResult;

use App\Models\Illuminate\Result;
use App\Repositories\TopResultRepositoryInterface;
use Illuminate\Support\Collection;

readonly class UpsertTopResultHandler
{
    public function __construct(
        private TopResultRepositoryInterface $repository,
    ) {
    }

    /**
     * @param Collection<Result> $results
     * @return void
     */
    public function handle(Collection $results): void
    {
        $this->repository->upsert($results);
    }
}
