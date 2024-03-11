<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Repositories\Meilisearch\Results\ResultCollection;

interface ResultRepositoryInterface
{
    /**
     * @param array<int> $ids
     * @return ResultCollection
     */
    public function byIds(array $ids): ResultCollection;
}
