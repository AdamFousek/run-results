<?php

declare(strict_types=1);


namespace App\Commands\Results;

readonly class CreateResult
{
    public function __construct(
        public int $raceId,
        public int $runnerId,
        public int $position,
        public int $startingNumber,
        public ?string $time,
        public int $categoryPosition,
        public string $category,
        public bool $dnf,
        public bool $dns,
    ) {
    }
}
