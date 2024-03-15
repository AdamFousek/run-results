<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Repositories\Meilisearch\Results\ResultCollection;
use App\Repositories\ResultRepositoryInterface;

readonly class GetResultsHandler
{
    public function __construct(
        private ResultRepositoryInterface $repository,
    ) {
    }

    public function handle(GetResultsQuery $query): ResultCollection
    {
        return $this->repository->byQuery($query);
    }
}
