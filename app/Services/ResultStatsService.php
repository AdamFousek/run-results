<?php

declare(strict_types=1);


namespace App\Services;

use App\Queries\Result\GetStatsByRaceTagHandler;
use App\Queries\Result\GetStatsByRaceTagQuery;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class ResultStatsService
{
    public function __construct(
        private readonly GetStatsByRaceTagHandler $getStatsByRaceIdsHandler,
    ) {
    }

    /**
     * @param string $raceTag
     * @return array{
     *     fastestTime: array{time: int, year: int},
     *     fastestMan: array{time: int, year: int},
     *     fastestWoman: array{time: int, year: int},
     *     averageTime: array{time: int}
     * }
     */
    public function provideStatsByRaceIds(string $raceTag): array
    {
        $cachedData = Cache::store('redis')->tags('result_stats_' . $raceTag)->get($raceTag, []);
        if ($cachedData !== []) {
            return $cachedData;
        }

        $stats = $this->getStatsByRaceIdsHandler->handle(new GetStatsByRaceTagQuery($raceTag));

        Cache::store('redis')->tags('result_stats_' . $raceTag)->put($raceTag, $stats, 60 * 60 * 24);

        return $stats;
    }

    public function invalidateCache(string $raceTag): void
    {
        Cache::store('redis')->tags('result_stats_' . $raceTag)->flush();
    }
}
