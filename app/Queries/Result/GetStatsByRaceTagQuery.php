<?php

declare(strict_types=1);


namespace App\Queries\Result;

readonly class GetStatsByRaceTagQuery
{
    /**
     * @param string $raceTag
     */
    public function __construct(
        public string $raceTag,
    ) {
    }
}
