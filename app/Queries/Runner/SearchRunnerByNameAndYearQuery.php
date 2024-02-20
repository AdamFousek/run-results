<?php

declare(strict_types=1);


namespace App\Queries\Runner;

readonly class SearchRunnerByNameAndYearQuery
{
    public function __construct(
        public string $lastName,
        public string $firstName,
        public int $year,
    ) {
    }
}
