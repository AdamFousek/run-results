<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate;

use App\Casts\TimeCast;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Illuminate\Result;
use App\Repositories\IlluminateResultRepositoryInterface;

class IlluminateResultRepository implements IlluminateResultRepositoryInterface
{

    public function __construct(
        private readonly TimeCast $timeCast
    ) {
    }

    public function getFastestTimeByRaceIds(string $raceTag): ?array
    {
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
            'time' => $fastestTimeResult->time,
            'year' => $fastestTimeResult->year,
        ];
    }

    public function getFastestManByRaceIds(string $raceTag): ?array
    {
        return $this->getFastestByGender($raceTag, RunnerGenderEnum::MALE);
    }

    public function getFastestWomenByRaceIds(string $raceTag): ?array
    {
        return $this->getFastestByGender($raceTag, RunnerGenderEnum::FEMALE);
    }

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
}
