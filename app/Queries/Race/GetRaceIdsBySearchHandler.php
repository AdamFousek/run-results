<?php

declare(strict_types=1);


namespace App\Queries\Race;

use App\Repositories\RaceRepositoryInterface;

class GetRaceIdsBySearchHandler
{
    public function __construct(
        private readonly RaceRepositoryInterface $raceRepository,
    ) {
    }

    /**
     * @param GetRaceIdsBySearch $search
     * @return int[]
     */
    public function handle(GetRaceIdsBySearch $search): array
    {
        return $this->raceRepository->getIds($search);
    }
}
