<?php

declare(strict_types=1);


namespace App\Http\Transformers\Race;

use App\Models\Illuminate\Race;
use Illuminate\Database\Eloquent\Collection;

class RaceListTransformer
{
    /**
     * @param array<int, ?Race> $items
     * @return array<array{
     *      id: int,
     *      name: string,
     *      slug: string,
     *      date: string|null,
     *      formDate: string|null,
     *      location: string|null,
     *      surface: string|null,
     *      type: string|null,
     *      tag: string|null,
     *      vintage: int|null,
     *      region: string|null,
     *      distance: string|null,
     *      distanceRaw: int|null,
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
     *     slug: string,
     *     date: string|null,
     *     formDate: string|null,
     *     location: string|null,
     *     surface: string|null,
     *     type: string|null,
     *     tag: string|null,
     *     vintage: int|null,
     *     latitude: float|null,
     *     longitude: float|null,
     *     region: string|null,
     *     distance: string|null,
     *     distanceRaw: int|null,
     *     resultsCount: int
     * }
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
            'tag' => $race->tag,
            'vintage' => $race->vintage,
            'region' => $race->region,
            'latitude' => $race->latitude,
            'longitude' => $race->longitude,
            'distance' => $race->distance,
            'distanceRaw' => $race->getRawOriginal('distance'),
            'resultsCount' => $race->results_count ?? 0,
            'createdAt' => $race->created_at?->format('j. n. Y H:i:s'),
        ];
    }
}
