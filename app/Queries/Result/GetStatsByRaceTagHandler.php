<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Repositories\IlluminateResultRepositoryInterface;

readonly class GetStatsByRaceTagHandler
{
    public function __construct(
        private IlluminateResultRepositoryInterface $resultRepository,
    ) {
    }

    /**
     * @param GetStatsByRaceTagQuery $query
     * @return array{
     *     fastestTime: array{time: int, year: int}|null,
     *     fastestMan: array{time: int, year: int}|null,
     *     fastestWoman: array{time: int, year: int}|null,
     *     averageTime: array{time: int}|null,
     *     }
     */
    public function handle(GetStatsByRaceTagQuery $query): array
    {
        return [
            'fastestTime' => $this->resultRepository->getFastestTimeByRaceIds($query->raceTag),
            'fastestMan' => $this->resultRepository->getFastestManByRaceIds($query->raceTag),
            'fastestWoman' => $this->resultRepository->getFastestWomanByRaceIds($query->raceTag),
            'averageTime' => $this->resultRepository->getAverageTimeByRaceIds($query->raceTag),
        ];
    }
}
