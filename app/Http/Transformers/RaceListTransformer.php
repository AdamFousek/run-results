<?php

declare(strict_types=1);


namespace App\Http\Transformers;

use App\Models\Race;
use App\Services\DistanceService;

class RaceListTransformer
{
    public function __construct(private readonly DistanceService $distanceService)
    {

    }

    /**
     * @param array<int, ?Race> $items
     * @return array<array<string, string>>
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
     * @return array<string, string>
     */
    private function transformItem(Race $race): array
    {
        return [
            'name' => $race->name,
            'date' => $race->date->format('j.n.Y'),
            'location' => $race->location,
            'distance' => $this->distanceService->transform($race->distance),
        ];
    }
}
