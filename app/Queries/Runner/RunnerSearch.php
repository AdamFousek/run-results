<?php

declare(strict_types=1);


namespace App\Queries\Runner;

readonly class RunnerSearch
{
    /**
     * @param string $search
     * @param int $page
     * @param int $perPage
     * @param string $sortBy
     * @param string $sortDirection
     * @param int[] $withoutIds
     */
    public function __construct(
        public string $search,
        public int $page,
        public int $perPage,
        public string $sortBy = '',
        public string $sortDirection = 'asc',
        public array $withoutIds = [],
    ) {
    }
}
