<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate;

use App\Casts\TimeCast;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Illuminate\Result;
use App\Models\Meilisearch\Runner;
use App\Models\QueryResult\TopRunner;
use App\Queries\Result\GetResultsQuery;
use App\Queries\Result\GetTopRunnersBy;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
use App\Repositories\Illuminate\Results\TopRunnersResult;
use App\Repositories\IlluminateResultRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

readonly class IlluminateResultRepository implements IlluminateResultRepositoryInterface
{
    public function __construct(
        private TimeCast $timeCast,
    ) {
    }

    /**
     * @param string $raceTag
     * @return array<string, int|string>|null
     */
    public function getFastestTimeByRaceTag(string $raceTag): ?array
    {
        return $this->getFastestByGender($raceTag);
    }

    /**
     * @param string $raceTag
     * @return array<string, int|string>|null
     */
    public function getFastestManByRaceTag(string $raceTag): ?array
    {
        return $this->getFastestByGender($raceTag, RunnerGenderEnum::MALE);
    }

    /**
     * @param string $raceTag
     * @return array<string, int|string>|null
     */
    public function getFastestWomanByRaceTag(string $raceTag): ?array
    {
        return $this->getFastestByGender($raceTag, RunnerGenderEnum::FEMALE);
    }

    /**
     * @param string $raceTag
     * @return array<string, int>|null
     */
    public function getAverageTimeByRaceIds(string $raceTag): ?array
    {
        $fastestTimeQuery= Result::query()
            ->selectRaw('AVG(results.time) as time, YEAR(races.date) as year')
            ->join('races', 'results.race_id', '=', 'races.id')
            ->whereNotNull('results.time')
            ->where('races.tag', $raceTag)
            ->groupBy('races.id', 'races.date')
            ->get();

        $average = 0;
        $total = 0;
        foreach ($fastestTimeQuery as $result) {
            if ($result->time === 0) {
                continue;
            }

            $average += $result->time;
            $total++;
        }

        if ($average === 0) {
            return null;
        }

        return [
            'time' => $this->timeCast->get(new Result(), '', (int)round($average / $total), []),
        ];
    }

    /**
     * @param string $raceTag
     * @param RunnerGenderEnum|null $gender
     * @return array<string, int|string>|null
     */
    private function getFastestByGender(string $raceTag, ?RunnerGenderEnum $gender = null): ?array
    {
        $fastestTimeQuery = Result::query()
            ->select(['results.*', 'runners.first_name', 'runners.last_name', 'races.date'])
            ->join('races', 'results.race_id', '=', 'races.id')
            ->join('runners', 'results.runner_id', '=', 'runners.id')
            ->where('results.time', '>', 0)
            ->where('races.tag', $raceTag);

        if ($gender !== null) {
            $fastestTimeQuery->where('runners.gender', '=', $gender->value);
        }

        /** @var ?Result $fastestTimeResult */
        $fastestTimeResult = $fastestTimeQuery->orderBy('results.time')
            ->first();

        if ($fastestTimeResult === null) {
            return null;
        }

        return [
            'runnerId' => $fastestTimeResult->runner_id,
            'name' => $fastestTimeResult->runner->full_name,
            'time' => (string)$fastestTimeResult->time,
            'year' => (int)$fastestTimeResult->race->date?->year,
        ];
    }

    /**
     * @param int $raceId
     * @return string[]
     */
    public function getCategoriesByRaceId(int $raceId): array
    {
        $results = Result::query()
            ->where('race_id', $raceId)
            ->groupBy('category');

        return $results->pluck('category')->toArray();
    }

    #[\Override]
    public function getTopRunnersBy(GetTopRunnersBy $query): Collection
    {
        $fastestRunners = DB::table('results')
            ->selectRaw('runner_id, MIN(results.time) as resultTime')
            ->join('races', 'results.race_id', '=', 'races.id')
            ->join('runners', 'results.runner_id', '=', 'runners.id')
            ->where('results.time', '>', 0)
            ->where('races.tag', $query->raceTag)
            ->orderBy('resultTime');

        if ($query->gender !== null) {
            $fastestRunners->where('runners.gender', '=', $query->gender->value);
        }

        $fastestRunners->groupBy('runner_id');

        $singleRace = DB::table('results')
            ->selectRaw('results.runner_id, MIN(results.race_id) as race_id')
            ->joinSub($fastestRunners, 'min', function ($join) {
                $join->on('results.runner_id', '=', 'min.runner_id');
                $join->on('results.time', '=', 'min.resultTime');
            })
            ->groupBy('results.runner_id');

        $results = Result::query()
            ->select('*')
            ->joinSub($singleRace, 'race',function ($join) {
                $join->on('results.runner_id', '=', 'race.runner_id');
                $join->on('results.race_id', '=', 'race.race_id');
            })
            ->orderBy('results.time')
            ->limit($query->limit)
            ->offset($query->offset)
            ->get();

        $results->load(['runner', 'race']);

        return collect($results);
    }

    public function getMostParticipants(GetTopRunnersBy $query): TopRunnersResult
    {
        $resultQuery = Result::query()
            ->selectRaw('count(*) as count, runner_id')
            ->join('races', 'results.race_id', '=', 'races.id')
            ->where('results.time', '>', 0)
            ->where('races.tag', $query->raceTag)
            ->groupBy('results.runner_id')
            ->orderBy('count', 'desc')
            ->limit($query->limit)
            ->get();

        $results = $resultQuery->load('runner');
        $topRunners = [];
        $index = 1;
        foreach ($results as $result) {
            $topRunners[] = new TopRunner(
                position: $index,
                runnerId: $result->runner_id,
                name: $result->runner->full_name,
                time: '',
                year: 0,
                runnerYear: $result->runner->year,
                participiantCount: $result->count,
            );

            $index++;
        }

        return new TopRunnersResult($topRunners, $results->count());
    }
}
