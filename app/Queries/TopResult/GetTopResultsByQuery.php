<?php

declare(strict_types=1);


namespace App\Queries\TopResult;

use App\Repositories\Meilisearch\Results\TopResultCollection;
use App\Repositories\TopResultRepositoryInterface;

readonly class GetTopResultsByQuery
{
    public function __construct(
        private TopResultRepositoryInterface $repository,
    ) {
    }

    public function handle(GetTopResultsQuery $query): TopResultCollection
    {
        return $this->repository->find($query);
    }
}
