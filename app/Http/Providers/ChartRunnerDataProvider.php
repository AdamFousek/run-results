<?php

declare(strict_types=1);


namespace App\Http\Providers;

use App\Casts\DistanceCast;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;
use Illuminate\Support\Carbon;
use Random\RandomException;
use stdClass;

class ChartRunnerDataProvider
{

    /**
     * @param Runner $runner
     * @return array{
     *     distance: array<int, array{
     *          label: string,
     *          data: int,
     *          color: string
     *     }>,
     *     surface: array<int, array{
     *          label: string,
     *          data: int,
     *          color: string
     *     }>,
     *     compare: array<string, array{
     *      datasets: array{
     *      label: string,
     *      color: string,
     *      data: array{
     *           y: int,
     *           x: string
     *       }[]
     *      }[],
     *      slowestTime: int,
     *      fastestTime: int|null
     *      }>|null
     * }
     * @throws RandomException
     */
    public function provide(Runner $runner): array
    {
        return [
            'distance' => $this->resolveDistanceChart($runner),
             // 'races' => $this->resolveRacesChart($runner),
            'surface' => $this->resolveSurfaceChart($runner),
            'compare' => $this->resolveCompareChart($runner),
        ];
    }

    /**
     * @param Runner $runner
     * @return array<int, array{
     *     label: string,
     *     data: int,
     *     color: string
     * }>
     * @throws RandomException
     */
    private function resolveDistanceChart(Runner $runner): array
    {
        $results = Result::query()
            ->selectRaw('count(*) as count, races.distance')
            ->join('races', 'races.id', '=', 'results.race_id')
            ->whereRunnerId($runner->id)
            ->groupBy('distance')
            ->get();

        $chartData = [];
        /** @var Result $result */
        foreach ($results as $result) {
            $castDistance = new DistanceCast();
            $chartData[] = [
                'label' => $castDistance->get($result, 'distance', $result->distance ?? 0),
                'data' => $result->count ?? 0,
                'color' => sprintf('#%06X', random_int(0, 0xFFFFFF)),
            ];
        }

        return $chartData;
    }

    /*private function resolveRacesChart(Runner $runner): array
    {
        $results = Result::query()
            ->selectRaw('count(*) as count, races.type')
            ->join('races', 'races.id', '=', 'results.race_id')
            ->whereRunnerId($runner->id)
            ->groupBy('type')
            ->get();

        $chartData = [];
        foreach ($results as $result) {
            $chartData[] = [
                'label' => $result->type,
                'data' => $result->count,
                'color' => sprintf('#%06X', random_int(0, 0xFFFFFF)),
            ];
        }

        return $chartData;
    }*/

    /**
     * @param Runner $runner
     * @return array<int, array{
     *     label: string,
     *     data: int,
     *     color: string
     * }>
     * @throws RandomException
     */
    private function resolveSurfaceChart(Runner $runner): array
    {
        $results = Result::query()
            ->selectRaw('count(*) as count, races.surface')
            ->join('races', 'races.id', '=', 'results.race_id')
            ->whereRunnerId($runner->id)
            ->groupBy('surface')
            ->get();

        $chartData = [];
        /** @var stdClass $result */
        foreach ($results as $result) {
            $chartData[] = [
                'label' => $result->surface ?? '',
                'data' => $result->count ?? 0,
                'color' => sprintf('#%06X', random_int(0, 0xFFFFFF)),
            ];
        }

        return $chartData;
    }

    /**
     * @param Runner $runner
     * @return array<string, array{
     *     datasets: array{
     *     label: string,
     *     color: string,
     *     data: array{
     *          y: int,
     *          x: string
     *      }[]
     *     }[],
     *     slowestTime: int,
     *     fastestTime: int|null
     * }>|null
     * @throws RandomException
     */
    private function resolveCompareChart(Runner $runner): ?array
    {
        $results = Result::query()->selectRaw('results.*, races.tag, races.date')->whereRunnerId($runner->id)
            ->join('races', 'races.id', '=', 'results.race_id')
            ->where(function($query) {
                return $query->where('tag', '!=', null)
                    ->orWhere('tag', '!=', '');
            })
            ->orderBy('races.date')
            ->get();

        $raceIds = $results->pluck('race_id');

        $averageResults = Result::query()->selectRaw('avg(results.time) as time, tag, date')
            ->join('races', 'races.id', '=', 'results.race_id')
            ->groupBy(['races.date', 'races.tag'])
            ->whereIn('race_id', $raceIds)
            ->where(function($query) {
                return $query->where('tag', '!=', null)
                    ->orWhere('tag', '!=', '');
            })
            ->orderBy('races.date')
            ->get()
            ->groupBy('tag');

        $results = $results->groupBy('tag');

        $chartData = [];
        foreach ($results as $key => $result) {
            $fastestTime = null;
            $slowestTime = 0;
            $runnerData = [];
            /** @var Result $item */
            foreach ($result as $item) {
                $time = (int)$item->getRawOriginal('time');
                if ($fastestTime === null) {
                    $fastestTime = $time;
                } else {
                    if ($fastestTime > $time) {
                        $fastestTime = $time;
                    }
                }
                if ($slowestTime < $time) {
                    $slowestTime = $time;
                }
                $runnerData[] = [
                    'y' => $time,
                    'x' => (new Carbon($item->date ?? 'now'))->format('j.n.Y'),
                ];
            }

            $averageData = [];
            /** @var stdClass $averageResult */
            foreach($averageResults->get($key) ?? [] as $averageResult) {
                $time = (int)$averageResult->time;
                if ($fastestTime === null) {
                    $fastestTime = $time;
                } else {
                    if ($fastestTime > $time) {
                        $fastestTime = $time;
                    }
                }
                if ($slowestTime < $time) {
                    $slowestTime = $time;
                }
                $averageData[] = [
                    'y' => (int)$averageResult->time,
                    'x' => (new Carbon($averageResult->date))->format('j.n.Y'),
                ];
            }

            $chartData[$key]['datasets'][] = [
                'label' => $runner->last_name . ' ' . $runner->first_name,
                'color' => sprintf('#%06X', random_int(0, 0xFFFFFF)),
                'data' => $runnerData,
            ];
            $chartData[$key]['datasets'][] = [
                'label' => trans('messages.chart_average'),
                'color' => sprintf('#%06X', random_int(0, 0xFFFFFF)),
                'data' => $averageData,
            ];
            $chartData[$key]['slowestTime'] = $slowestTime;
            $chartData[$key]['fastestTime'] = $fastestTime;
        }

        if ($chartData === []) {
            return null;
        }

        return $chartData;
    }
}
