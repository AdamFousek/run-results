<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch\Results;

use App\Models\Meilisearch\Result\Result;
use Illuminate\Support\Collection;

class ResultCollection
{
    /**
     * @param Collection<Result> $items
     * @param int $total
     * @param int $estimatedTotal
     */
    public function __construct(
        public Collection $items,
        public int $total,
        public int $estimatedTotal,
    ) {
    }
}
