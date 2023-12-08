<?php

declare(strict_types=1);


namespace App\Http\Transformers;

use App\Models\Race;

class RaceTransformer
{
    /**
     * @param Race $race
     * @return array<string, mixed>
     */
    public function transform(Race $race): array
    {
        return [
            'id' => $race->id,
            'name' => $race->name,
            'date' => $race->date->format('j. n. Y'),
            'description' => $race->description,
            'location' => $race->location,
            'distance' => $race->distance,
            'surface' => $race->surface,
            'type' => $race->type,
            'runners' => $race->runners->count(),
        ];
    }
}
