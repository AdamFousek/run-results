<?php

declare(strict_types=1);


namespace App\Http\Transformers\Runner;

use App\Models\Illuminate\Result;
use Illuminate\Database\Eloquent\Collection;

class RunnerRaceListTransformer
{
    /**
     * @param array<int, Result>|Collection $results
     * @return array<int, array{
     *     race_id: int,
     *     name: string,
     *     raceSlug: string,
     *     date: string|null,
     *     location: string|null,
     *     distance: string|null,
     *     time: string,
     *     position: int,
     *     category: string,
     *     category_position: int,
     *     club: string
     * }>
     */
    public function transform(array|Collection $results): array
    {
        $transformedData = [];
        foreach ($results as $result) {
            if (!$result instanceof Result) {
                continue;
            }

            $transformedData[] = [
                'race_id' => $result->race->id,
                'name' => $result->race->name,
                'raceSlug' => $result->race->slug,
                'date' => $result->race->date?->format('j.n.Y'),
                'location' => $result->race->location,
                'distance' => $result->race->distance,
                'time' => explode('.', $result->time ?? '')[0],
                'position' => $result->position,
                'category' => $result->category,
                'category_position' => $result->category_position,
                'club' => $result->club ?? '',
            ];
        }

        return $transformedData;
    }
}
