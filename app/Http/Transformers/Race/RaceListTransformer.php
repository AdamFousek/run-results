<?php

declare(strict_types=1);


namespace App\Http\Transformers\Race;

use App\Models\Race;

class RaceListTransformer
{
    /**
     * @param array<int, ?Race> $items
     * @return array<array<string, string|int|float>>
     */
    public function transform(array $items): array
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
            'date' => $race->date->format('j.n.Y'),
            'location' => $race->location,
            'distance' => $race->distance,
        ];
    }
}
