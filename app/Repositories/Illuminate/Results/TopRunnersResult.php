<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate\Results;

use App\Models\QueryResult\TopRunner;

readonly class TopRunnersResult
{
    /**
     * @param TopRunner[] $items
     * @param int $total
     */
    public function __construct(
        public array $items,
        public int $total,
    ) {
    }
}
