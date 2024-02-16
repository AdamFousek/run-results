<?php

declare(strict_types=1);


namespace App\Serializer;

use App\Models\Illuminate\Race;

class RaceSerializer
{
    /**
     * @param Race $race
     * @return array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     description: string,
     *     date: int|null,
     *     time: string|null,
     *     location: string,
     *     region: string,
     *     distance: int,
     *     vintage: int,
     *     surface: string,
     *     type: string,
     *     tag: string,
     *     isParent: bool,
     *     parent: array{id: int, name: string, slug: string}|null,
     *     _geo: array{lat: float, lng: float},
     *     files: array{
     *     id: int,
     *     name: string,
     *     url: string,
     *     isPublic: bool
     *     }[],
     *     runnerCount: int,
     *     createdAt: int|null,
     *     updatedAt: int|null,
     *     upsertedAt: int
     * }
     */
    public function serialize(Race $race): array
    {
        return [
            'id' => $race->id,
            'name' => $race->name,
            'slug' => $race->slug,
            'description' => $race->description->toPlainText(),
            'date' => $race->date?->getTimestamp(),
            'time' => $race->time?->format('H:i'),
            'location' => $race->location,
            'region' => $race->region,
            'distance' => $race->getRawOriginal('distance'),
            'vintage' => $race->vintage,
            'surface' => $race->surface,
            'type' => $race->type,
            'tag' => $race->tag,
            'isParent' => (bool)$race->is_parent,
            'parent' => $race->parent !== null ? [
                'id' => $race->parent->id,
                'name' => $race->parent->name,
                'slug' => $race->parent->slug,
            ] : null,
            '_geo' => [
                'lat' => $race->latitude,
                'lng' => $race->longitude,
            ],
            'files' => $this->resolveFiles($race),
            'runnerCount' => $race->results->count(),
            'createdAt' => $race->created_at?->getTimestamp(),
            'updatedAt' => $race->updated_at?->getTimestamp(),
            'upsertedAt' => now()->getTimestamp(),
        ];
    }

    private function resolveFiles(Race $race): array
    {
        $result = [];
        foreach ($race->files as $file) {
            $result[] = [
                'id' => $file->id,
                'name' => $file->name,
                'url' => $file->file_path,
                'isPublic' => (bool)$file->is_public,
            ];
        }

        return $result;
    }
}
