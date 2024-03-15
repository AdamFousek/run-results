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
     *     fastestTime: array{time: int, year: int, name: string, runnerId: int}|null,
     *     fastestMan: array{time: int, year: int, name: string, runnerId: int}|null,
     *     fastestWoman: array{time: int, year: int, name: string, runnerId: int}|null,
     *     averageTime: array{time: int}|null,
     *     }
     */
    public function handle(GetStatsByRaceTagQuery $query): array
    {
        return [
            'fastestTime' => $this->resultRepository->getFastestTimeByRaceTag($query->raceTag),
            'fastestMan' => $this->resultRepository->getFastestManByRaceTag($query->raceTag),
            'fastestWoman' => $this->resultRepository->getFastestWomanByRaceTag($query->raceTag),
            'averageTime' => $this->resultRepository->getAverageTimeByRaceIds($query->raceTag),
        ];
    }
}
