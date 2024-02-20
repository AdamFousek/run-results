<?php

declare(strict_types=1);


namespace App\Queries\Runner;

use App\Repositories\Meilisearch\Results\RunnerCollection;
use App\Repositories\RunnerRepository;

class SearchRunnerByNameAndYearHandler
{
    public function __construct(
        private readonly RunnerRepository $repository,
    ) {
    }

    public function handle(SearchRunnerByNameAndYearQuery $query): RunnerCollection
    {
        return $this->repository->searchByNameAndYear($query->lastName, $query->firstName, $query->year);
    }
}
