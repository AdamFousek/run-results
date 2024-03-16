<?php

declare(strict_types=1);


namespace App\Queries\Runner;

use App\Repositories\Meilisearch\Results\RunnerCollection;
use App\Repositories\RunnerRepositoryInterface;

readonly class RunnerSearchQuery
{
    public function __construct(
        private RunnerRepositoryInterface $repository,
    ) {
    }

    public function handle(RunnerSearch $query): RunnerCollection
    {
        return $this->repository->search($query);
    }
}
