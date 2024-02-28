<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Queries\Result\GetResultsQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IlluminateResultRepositoryInterface
{
    /**
     * @param string $raceTag
     * @return ?array{time: int, year: int}
     */
    public function getFastestTimeByRaceIds(string $raceTag): ?array;

    /**
     * @param string $raceTag
     * @return ?array{time: int, year: int}
     */
    public function getFastestManByRaceIds(string $raceTag): ?array;

    /**
     * @param string $raceTag
     * @return ?array{time: int, year: int}
     */
    public function getFastestWomanByRaceIds(string $raceTag): ?array;

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
}
