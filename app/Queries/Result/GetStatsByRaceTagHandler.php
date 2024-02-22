<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Repositories\IlluminateResultRepositoryInterface;

class GetStatsByRaceTagHandler
{
    public function __construct(
        private readonly IlluminateResultRepositoryInterface $resultRepository,
    ) {
    }

    /**
     * @param GetStatsByRaceTagQuery $query
     * @return array{
     *     fastestTime: array{time: int, year: int},
     *     fastestMan: array{time: int, year: int},
     *     fastestWomen: array{time: int, year: int},
     *     averageTime: array{time: int},
     *     }
     */
    public function handle(GetStatsByRaceTagQuery $query): array
    {
        return [
            'fastestTime' => $this->resultRepository->getFastestTimeByRaceIds($query->raceTag),
            'fastestMan' => $this->resultRepository->getFastestManByRaceIds($query->raceTag),
            'fastestWomen' => $this->resultRepository->getFastestWomenByRaceIds($query->raceTag),
            'averageTime' => $this->resultRepository->getAverageTimeByRaceIds($query->raceTag),
        ];
    }
}
