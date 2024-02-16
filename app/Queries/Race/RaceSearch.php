<?php

declare(strict_types=1);


namespace App\Queries\Race;

readonly class RaceSearch
{
    public function __construct(
        public string $search,
        public int $page,
        public int $perPage,
        public bool $wihtoutParent = false,
    ) {
    }
}
