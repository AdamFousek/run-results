<?php

declare(strict_types=1);


namespace App\Http\Transformers;

use App\Models\Result;
use Illuminate\Database\Eloquent\Collection;

class RunnerRaceListTransformer
{
    /**
     * @param Collection $results
     * @return array<int, array<string, mixed>>
     */
    public function transform(Collection $results): array
    {
        $transformedData = [];
        foreach ($results as $result) {
            if (!$result instanceof Result) {
                continue;
            }

            $transformedData[] = [
                'race_id' => $result->race->id,
                'name' => $result->race->name,
                'date' => $result->race->date->format('j.n.Y'),
                'location' => $result->race->location,
                'distance' => $result->race->distance,
                'time' => $result->time,
                'position' => $result->position,
                'category_position' => $result->category_position,
            ];
        }

        return $transformedData;
    }
}
