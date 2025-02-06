<?php

declare(strict_types=1);


namespace App\Queries\Illuminate\Race;

readonly class IlluminateSearchRaceQuery
{
    public function __construct(
        public string $search,
        public int $page = 1,
        public int $perPage = 50,
        public bool $wihtoutParent = false,
        public string $sortBy = '',
        public string $sortDirection = 'asc',
    ) {
    }
}
