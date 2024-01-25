<?php

declare(strict_types=1);


namespace App\Http\Transformers\Race;

use App\Models\Race;
use Illuminate\Database\Eloquent\Collection;

class RaceListTransformer
{
    /**
     * @param array<int, ?Race> $items
     * @return array<array<string, string|int|float>>
     */
    public function transform(Collection|array $items): array
    {
        $transformedItems = [];
        foreach ($items as $item) {
            if (!$item instanceof Race) {
                continue;
            }

            $transformedItems[] = $this->transformItem($item);
        }

        return $transformedItems;
    }

    /**
     * @param Race $race
     * @return array<string, string|int|float>
     */
    private function transformItem(Race $race): array
    {
        return [
            'id' => $race->id,
            'name' => $race->name,
            'slug' => $race->slug,
            'date' => $race->date?->format('j. n. Y'),
            'formDate' => $race->date?->format('Y-m-d'),
            'location' => $race->location,
            'surface' => $race->surface,
            'type' => $race->type,
            'distance' => $race->distance,
            'distanceRaw' => $race->getRawOriginal('distance'),
        ];
    }
}