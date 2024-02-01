<?php

declare(strict_types=1);


namespace App\Http\Providers;

use App\Casts\DistanceCast;
use App\Models\Result;
use App\Models\Runner;
use Random\RandomException;

class ChartRunnerDataProvider
{
    public function provide(Runner $runner): array
    {
        return [
            'distance' => $this->resolveDistanceChart($runner),
            'races' => $this->resolveRacesChart($runner),
        ];
    }

    /**
     * @param Runner $runner
     * @return array<int, array<string, mixed>>
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
        foreach ($results as $result) {
            $castDistance = new DistanceCast();
            $chartData[] = [
                'label' => $castDistance->get($result, 'distance', $result->distance, []),
                'data' => $result->count,
                'color' => sprintf('#%06X', random_int(0, 0xFFFFFF)),
            ];
        }

        return $chartData;
    }

    /**
     * @param string $defaultColor - Preferable hex color of type FF0000, 00FF00, 0000FF
     * @param int $count
     * @return array
     */
    private function getColors(string $defaultColor = 'FF0000', int $count = 1): array
    {
        $colors = [];
        for ($i = 0; $i < $count; $i++) {
            $colorVariation = -($i * 20);
        }
        return $colors;
    }

    private function resolveRacesChart(Runner $runner): array
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
    }
}
