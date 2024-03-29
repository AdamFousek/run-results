<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Models\Illuminate\Result;
use App\Queries\Result\GetResultsQuery;
use App\Queries\Result\GetTopRunnersBy;
use App\Repositories\Illuminate\Results\TopRunnersResult;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
     * @param GetTopRunnersBy $query
     * @return Collection<Result>
     */
    public function getTopRunnersBy(GetTopRunnersBy $query): Collection;

    public function getMostParticipants(GetTopRunnersBy $query): TopRunnersResult;
}
