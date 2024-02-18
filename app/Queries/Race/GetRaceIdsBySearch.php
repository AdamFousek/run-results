<?php

declare(strict_types=1);


namespace App\Queries\Race;

readonly class GetRaceIdsBySearch
{
    public function __construct(
        public string $search,
    ) {
    }
}
