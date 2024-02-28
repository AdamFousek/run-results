<?php

declare(strict_types=1);


namespace App\Services;

use App\Queries\Result\GetStatsByRaceTagHandler;
use App\Queries\Result\GetStatsByRaceTagQuery;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

readonly class ResultStatsService
{
    public function __construct(
        private GetStatsByRaceTagHandler $getStatsByRaceIdsHandler,
    ) {
    }

    /**
     * @param string $raceTag
     * @return array{
     *     fastestTime: array{time: int, year: int}|null,
     *     fastestMan: array{time: int, year: int}|null,
     *     fastestWoman: array{time: int, year: int}|null,
     *     averageTime: array{time: int}|null,
     * }
     */
    public function provideStatsByRaceIds(string $raceTag): array
    {
        /**
         * @var array{
         *     fastestTime: array{time: int, year: int}|null,
         *     fastestMan: array{time: int, year: int}|null,
         *     fastestWoman: array{time: int, year: int}|null,
         *     averageTime: array{time: int}|null,
         *     }|array{} $cachedData
         */
        $cachedData = Cache::store('redis')->tags('result_stats_' . $raceTag)->get($raceTag, []);
        if ($cachedData !== []) {
            return $cachedData;
        }

        $stats = $this->getStatsByRaceIdsHandler->handle(new GetStatsByRaceTagQuery($raceTag));

        Cache::store('redis')->tags('result_stats_' . $raceTag)->put($raceTag, $stats, 60 * 60 * 24);

        return $stats;
    }

    public function invalidateCache(?string $raceTag): void
    {
        if ($raceTag === null) {
            return;
        }

        Cache::store('redis')->tags('result_stats_' . $raceTag)->flush();
    }
}
