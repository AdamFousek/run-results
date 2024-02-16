<?php

declare(strict_types=1);


namespace App\Http\Transformers\Meilisearch;

use App\Casts\DistanceCast;
use App\Models\Meilisearch\Race;
use Illuminate\Support\Collection;

class RaceListTransformer
{
    /**
     * @param Collection<Race> $collection
     * @return array{array{id: int, firstName: string, lastName: string, year: int, resultsCount: int}}
     */
    public function transform(Collection $collection): array
    {
        $result = [];
        foreach ($collection as $race) {
            if (!$race instanceof Race) {
                continue;
            }

            $distanceCast = new DistanceCast();

            $result[] = [
                'id' => $race->getId(),
                'name' => $race->getName(),
                'slug' => $race->getSlug(),
                'description' => $race->getDescription(),
                'date' => $race->getDate()?->format('j. n. Y'),
                'time' => $race->getTime(),
                'location' => $race->getLocation(),
                'region' => $race->getRegion(),
                'distance' => $distanceCast->get(new \App\Models\Illuminate\Race(), 'distance', $race->getDistance()),
                'vintage' => $race->getVintage(),
                'surface' => $race->getSurface(),
                'type' => $race->getType(),
                'tag' => $race->getTag(),
                'isParent' => $race->isParent(),
                'parent' => $race->getParent(),
                'latitude' => $race->getLatitude(),
                'longitude' => $race->getLongitude(),
                'resultsCount' => $race->getResultsCount(),
                'files' => $race->getFiles(),
                'createdAt' => $race->getCreatedAt()?->getTimestamp(),
                'updatedAt' => $race->getUpdatedAt()?->getTimestamp(),
                'upsertedAt' => $race->getUpsertedAt()->getTimestamp(),
            ];
        }

        return $result;
    }
}
