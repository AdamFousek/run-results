<?php

declare(strict_types=1);


namespace App\Services\Providers;

use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\QueryResult\TopRunner;
use App\Queries\Result\GetStatsByRaceTagHandler;
use App\Queries\Result\GetStatsByRaceTagQuery;
use App\Queries\Result\GetTopRunnersBy;
use App\Queries\Result\GetTopRunnersByQuery;
use App\Repositories\Illuminate\Results\TopRunnersResult;
use Illuminate\Support\Facades\Cache;

readonly class ResultStatsService
{
    public function __construct(
        private GetStatsByRaceTagHandler $getStatsByRaceIdsHandler,
        private GetTopRunnersByQuery $getTopRunnersByQuery,
    ) {
    }

    /**
     * @param string $raceTag
     * @return array{
     *     fastestTime: array{time: int, year: int, name: string, runnerId: int}|null,
     *     fastestMan: array{time: int, year: int, name: string, runnerId: int}|null,
     *     fastestWoman: array{time: int, year: int, name: string, runnerId: int}|null,
     *     averageTime: array{time: int}|null,
     * }
     */
    public function provideStatsByRaceIds(string $raceTag): array
    {
        /**
         * @var array{
         *     fastestTime: array{time: int, year: int, name: string, runnerId: int}|null,
         *     fastestMan: array{time: int, year: int, name: string, runnerId: int}|null,
         *     fastestWoman: array{time: int, year: int, name: string, runnerId: int}|null,
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
        Cache::store('redis')->tags('top_women_' . $raceTag)->flush();
        Cache::store('redis')->tags('top_men_' . $raceTag)->flush();
        Cache::store('redis')->tags('top_participant_' . $raceTag)->flush();
    }

    /**
     * @param string $tag
     * @return TopRunnersResult
     */
    public function provideTopParticipantByRaceTag(string $tag): TopRunnersResult
    {
        /** @var TopRunnersResult $cachedData */
        $cachedData = new TopRunnersResult([], 0); //Cache::store('redis')->tags('top_participant_' . $tag)->get($tag, new TopRunnersResult([], 0));
        if ($cachedData->total !== 0) {
            return $cachedData;
        }

        $stats = $this->getTopRunnersByQuery->handle(new GetTopRunnersBy(
            raceTag: $tag,
            gender: null,
            limit: 10,
            isParticipation: true,
        ));

        Cache::store('redis')->tags('top_participant_' . $tag)->put($tag, $stats, 60 * 60 * 24);

        return $stats;
    }

    /**
     * @param string $tag
     * @return TopRunnersResult
     */
    public function provideTopWomenByRaceTag(string $tag): TopRunnersResult
    {
        /** @var TopRunnersResult $cachedData */
        $cachedData = new TopRunnersResult([], 0); //Cache::store('redis')->tags('top_women_' . $tag)->get($tag, new TopRunnersResult([], 0));
        if ($cachedData->total !== 0) {
            return $cachedData;
        }

        $stats = $this->getTopRunnersByQuery->handle(new GetTopRunnersBy(
            raceTag: $tag,
            gender: RunnerGenderEnum::FEMALE,
            limit: 10,
            isParticipation: false,
        ));

        Cache::store('redis')->tags('top_women_' . $tag)->put($tag, $stats, 60 * 60 * 24);

        return $stats;
    }

    /**
     * @param string $tag
     * @return TopRunnersResult
     */
    public function provideTopMenByRaceTag(string $tag): TopRunnersResult
    {
        /** @var TopRunnersResult $cachedData */
        $cachedData = new TopRunnersResult([], 0); //Cache::store('redis')->tags('top_men_' . $tag)->get($tag, new TopRunnersResult([], 0));
        if ($cachedData->total !== 0) {
            return $cachedData;
        }

        $stats = $this->getTopRunnersByQuery->handle(new GetTopRunnersBy(
            raceTag: $tag,
            gender: RunnerGenderEnum::MALE,
            limit: 10,
            isParticipation: false,
        ));

        Cache::store('redis')->tags('top_men_' . $tag)->put($tag, $stats, 60 * 60 * 24);

        return $stats;
    }

    /**
     * @param array{items: array<string, int|string>, count: int} $data
     * @return TopRunnersResult
     */
    private function transformCache(array $data): TopRunnersResult
    {
        $runners = [];
        foreach ($data['items'] as $item) {
            $runners[] = new TopRunner(
                runnerId: (int)($item['runnerId'] ?? 0),
                name: (string)($item['name'] ?? ''),
                time: (string)($item['time'] ?? ''),
                year: (int)($item['year'] ?? 0),
                participiantCount: (int)($item['participiantCount'] ?? 0),
            );
        }

        return new TopRunnersResult($runners, $data['count']);
    }
}
