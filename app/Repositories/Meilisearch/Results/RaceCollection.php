<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch\Results;

use App\Models\Meilisearch\Race;
use Illuminate\Support\Collection;

class RaceCollection
{
    /**
     * @param Collection<Race> $items
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
