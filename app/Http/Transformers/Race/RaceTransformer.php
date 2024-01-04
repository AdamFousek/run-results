<?php

declare(strict_types=1);


namespace App\Http\Transformers\Race;

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
            'slug' => $race->slug,
            'date' => $race->date->format('j. n. Y'),
            'description' => $race->description,
            'location' => $race->location,
            'distance' => $race->distance,
            'surface' => $race->surface,
            'type' => $race->type,
            'runners' => $race->runners->count(),
            'isParent' => $race->is_parent,
            'parentId' => $race->parent_id,
            'parentName' => $race->parent?->name,
        ];
    }
}
