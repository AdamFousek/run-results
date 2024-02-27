<?php

declare(strict_types=1);


namespace App\Http\Transformers\Race;

use App\Models\Illuminate\Race;
use Illuminate\Database\Eloquent\Collection;

class RaceResultsTransformer
{
    /**
     * @param array<int, ?Race> $items
     * @return array<array{
     *      id: int,
     *      name: string,
     *      date: string|null,
     *      location: string|null,
     *      resultsCount: int
     *  }>
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
     * @return array{
     *     id: int,
     *     name: string,
     *     date: string|null,
     *     location: string|null,
     *     resultsCount: int
     * }
     */
    private function transformItem(Race $race): array
    {
        return [
            'id' => $race->id,
            'name' => $race->name,
            'date' => $race->date?->format('j. n. Y'),
            'location' => $race->location,
            'resultsCount' => $race->results_count ?? 0,
        ];
    }
}
