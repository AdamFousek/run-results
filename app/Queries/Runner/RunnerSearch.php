<?php

declare(strict_types=1);


namespace App\Queries\Runner;

readonly class RunnerSearch
{
    public function __construct(
        public string $search,
        public int $page,
        public int $perPage,
    ) {
    }
}
