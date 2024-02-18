<?php

declare(strict_types=1);


namespace App\Queries\Race;

use App\Repositories\RaceRepository;

class GetRaceIdsBySearchHandler
{
    public function __construct(
        private readonly RaceRepository $raceRepository,
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
