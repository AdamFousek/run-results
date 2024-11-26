<?php

declare(strict_types=1);


namespace App\Serializer;

use App\Models\Illuminate\Race;
use App\Models\IlluminateModel;

class RaceSerializer implements ShouldSerialize
{
    /**
     * @param Race $model
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
     *     vintage: int|null,
     *     surface: string,
     *     type: string,
     *     tag: string,
     *     isParent: bool,
     *     parent: array{id: int, name: string, slug: string}|null,
     *     _geo: array{lat: float|null, lng: float|null},
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
    public function serialize(IlluminateModel $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'slug' => $model->slug,
            'description' => $model->description->toPlainText(),
            'date' => $model->date?->getTimestamp(),
            'time' => $model->time?->format('H:i'),
            'location' => $model->location ?? '',
            'region' => $model->region,
            'distance' => $model->getRawOriginal('distance'),
            'vintage' => $model->vintage,
            'surface' => $model->surface ?? '',
            'type' => $model->type ?? '',
            'tag' => $model->tag ?? '',
            'isParent' => (bool)$model->is_parent,
            'parent' => $model->parent !== null ? [
                'id' => $model->parent->id,
                'name' => $model->parent->name,
                'slug' => $model->parent->slug,
            ] : null,
            '_geo' => [
                'lat' => $model->latitude,
                'lng' => $model->longitude,
            ],
            'files' => $this->resolveFiles($model),
            'runnerCount' => $model->results->count(),
            'createdAt' => $model->created_at?->getTimestamp(),
            'updatedAt' => $model->updated_at?->getTimestamp(),
            'upsertedAt' => now()->getTimestamp(),
        ];
    }

    /**
     * @param Race $race
     * @return array<int, array{id: int, name: string, url: string, isPublic: bool}>
     */
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
