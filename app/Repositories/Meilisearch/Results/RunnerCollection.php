<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch\Results;

use App\Models\Meilisearch\Runner;
use Illuminate\Support\Collection;

readonly class RunnerCollection
{
    /**
     * @param Collection<Runner> $items
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
