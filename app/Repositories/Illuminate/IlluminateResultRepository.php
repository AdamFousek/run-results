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
use stdClass;

readonly class IlluminateResultRepository implements IlluminateResultRepositoryInterface
{

    private const int LIMIT_RUNNERS = 50;

    public function __construct(
        private TimeCast $timeCast,
        private RunnerSearchQuery $runnerSearchQuery,
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
            'time' => $fastestTimeResult->time,
            'year' => $fastestTimeResult->race->date?->year,
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

    public function findResults(GetResultsQuery $query): LengthAwarePaginator
    {
        $search = $query->search;
        $race = $query->race;
        $page = $query->page;
        $showFemale = $query->showFemale;
        $showMale = $query->showMale;
        $categories = $query->categories;

        if ($search !== '') {
            if (is_numeric($search)) {
                return Result::whereRaceId($race->id)->whereStartingNumber((int)$search)->orderBy('position')->paginate(self::LIMIT_RUNNERS);
            }

            $runners = $this->runnerSearchQuery->handle(new RunnerSearch($search, $page, 100000));
            $runnerIds = $runners->items->map(fn(Runner $runner) => $runner->getId())->toArray();
            return Result::whereRaceId($race->id)->orderBy('position')->with('runner')->whereIn('runner_id', $runnerIds)->paginate(self::LIMIT_RUNNERS);
        }

        $query = Result::whereRaceId($race->id)->with('runner');

        if (!$showFemale) {
            $query->withoutFemale();
        }

        if (!$showMale) {
            $query->withoutMale();
        }

        if ($categories !== []) {
            $query->whereIn('category', $categories);
        }

        return $query->orderBy('position')->paginate(self::LIMIT_RUNNERS);
    }

    #[\Override]
    public function getTopRunnersBy(GetTopRunnersBy $query): TopRunnersResult
    {
        $resultQuery = Result::query()
            ->select(['results.*', 'runners.first_name', 'runners.last_name'])
            ->join('races', 'results.race_id', '=', 'races.id')
            ->join('runners', 'results.runner_id', '=', 'runners.id')
            ->where('results.time', '>', 0)
            ->where('races.tag', $query->raceTag);

        if ($query->gender !== null) {
            $resultQuery->where('runners.gender', '=', $query->gender->value);
        }

        $result = $resultQuery->orderBy('results.time')->limit($query->limit)->get();
        $result->load(['runner', 'race']);
        $topRunners = [];
        foreach ($result as $res) {
            $topRunner = new TopRunner(
                runnerId: $res->runner_id,
                name: $res->runner->full_name,
                time: $res->time,
                year: $res->race->date?->year ?? 0,
                participiantCount: 0,
            );

            $topRunners[] = $topRunner;
        }

        return new TopRunnersResult($topRunners, $result->count());
    }
}
