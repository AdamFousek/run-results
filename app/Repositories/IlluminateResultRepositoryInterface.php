<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Queries\Result\GetResultsQuery;
use App\Queries\Result\GetTopRunnersBy;
use App\Repositories\Illuminate\Results\TopRunnersResult;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IlluminateResultRepositoryInterface
{
    /**
     * @param string $raceTag
     * @return ?array{time: int, year: int, name: string, runnerId: int}
     */
    public function getFastestTimeByRaceTag(string $raceTag): ?array;

    /**
     * @param string $raceTag
     * @return ?array{time: int, year: int, name: string, runnerId: int}
     */
    public function getFastestManByRaceTag(string $raceTag): ?array;

    /**
     * @param string $raceTag
     * @return ?array{time: int, year: int, name: string, runnerId: int}
     */
    public function getFastestWomanByRaceTag(string $raceTag): ?array;

    /**
     * @param string $raceTag
     * @return ?array{time: int}
     */
    public function getAverageTimeByRaceIds(string $raceTag): ?array;

    /**
     * @param int $raceId
     * @return string[]
     */
    public function getCategoriesByRaceId(int $raceId): array;

    /**
     * @param GetResultsQuery $query
     * @return LengthAwarePaginator
     */
    public function findResults(GetResultsQuery $query): LengthAwarePaginator;

    /**
     * @param GetTopRunnersBy $query
     * @return TopRunnersResult
     */
    public function getTopRunnersBy(GetTopRunnersBy $query): TopRunnersResult;
}
