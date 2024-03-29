<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Queries\Result\GetResultsQuery;
use App\Repositories\Meilisearch\Results\ResultCollection;

interface ResultRepositoryInterface
{
    /**
     * @param array<int> $ids
     * @return ResultCollection
     */
    public function byIds(array $ids): ResultCollection;

    public function byQuery(GetResultsQuery $query): ResultCollection;

    /**
     * @param int[] $ids
     * @return void
     */
    public function delete(array $ids): void;
}
