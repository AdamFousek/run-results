<?php

declare(strict_types=1);


namespace App\Models\QueryResult;

readonly class TopRunner
{
    public function __construct(
        public int $position,
        public int $runnerId,
        public string $name,
        public string $time,
        public int $year,
        public int $runnerYear,
        public int $participiantCount = 0,
    ) {
    }
}
