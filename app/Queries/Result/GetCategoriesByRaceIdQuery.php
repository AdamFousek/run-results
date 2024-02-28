<?php

declare(strict_types=1);


namespace App\Queries\Result;

readonly class GetCategoriesByRaceIdQuery
{
    public function __construct(
        public int $raceId,
    ) {
    }
}
