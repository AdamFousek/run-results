<?php

declare(strict_types=1);


namespace App\Queries\Race;

readonly class RaceSearch
{
    public function __construct(
        public string $search,
        public int $page = 1,
        public int $perPage = 50,
        public bool $wihtoutParent = false,
        public string $filterBy = '',
        public string $filterDirection = 'asc',
    ) {
    }
}
