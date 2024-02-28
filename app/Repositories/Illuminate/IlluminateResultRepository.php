<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate;

use App\Casts\TimeCast;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Illuminate\Result;
use App\Models\Meilisearch\Runner;
use App\Queries\Result\GetResultsQuery;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
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
     * @return array<string, int>|null
     */
    public function getFastestTimeByRaceIds(string $raceTag): ?array
    {
        /** @var ?stdClass $fastestTimeResult */
        $fastestTimeResult= Result::query()
            ->selectRaw('MIN(results.time) as time, YEAR(races.date) as year')
            ->join('races', 'results.race_id', '=', 'races.id')
            ->whereNotNull('results.time')
            ->where('races.tag', $raceTag)
            ->where('results.time', '>', 0)
            ->orderBy('time')
            ->groupBy('races.id', 'races.date')
            ->first();

        if ($fastestTimeResult === null) {
            return null;
        }

        return [
            'time' => $fastestTimeResult->time ?? 0,
            'year' => $fastestTimeResult->year ?? 0,
        ];
    }

    /**
     * @param string $raceTag
     * @return array<string, int>|null
     */
    public function getFastestManByRaceIds(string $raceTag): ?array
    {
        return $this->getFastestByGender($raceTag, RunnerGenderEnum::MALE);
    }

    /**
     * @param string $raceTag
     * @return array<string, int>|null
     */
    public function getFastestWomanByRaceIds(string $raceTag): ?array
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
     * @return array<string, int>|null
     */
    private function getFastestByGender(string $raceTag, ?RunnerGenderEnum $gender = null): ?array
    {
        $fastestTimeQuery = Result::query()
            ->selectRaw('MIN(results.time) as time, YEAR(races.date) as year')
            ->join('races', 'results.race_id', '=', 'races.id')
            ->join('runners', 'results.runner_id', '=', 'runners.id')
            ->whereNotNull('results.time')
            ->where('results.time', '>', 0)
            ->where('races.tag', $raceTag);


        if ($gender !== null) {
            $fastestTimeQuery->where('runners.gender', '=', $gender->value);
        }

        /** @var ?stdClass $fastestTimeResult */
        $fastestTimeResult = $fastestTimeQuery->groupBy('races.id', 'races.date')
            ->orderBy('time')
            ->first();

        if ($fastestTimeResult === null) {
            return null;
        }

        return [
            'time' => $fastestTimeResult->time,
            'year' => $fastestTimeResult->year,
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
}
