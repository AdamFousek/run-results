<?php

declare(strict_types=1);


namespace App\Serializer;

use App\Models\Illuminate\Race;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;
use App\Models\IlluminateModel;

class ResultSerializer implements ShouldSerialize
{
    /**
     * @param Result $model
     * @return array{
     *     id: int,
     *     race: array{
     *      id: int,
     *      name: string,
     *      slug: string,
     *      tag: string|null,
     *      date: int|null,
     *      time: string|null,
     *      vintage: int|null,
     *      distance: int|null,
     *      location: string|null,
     *      surface: string|null,
     *      type: string|null,
     *      region: string|null,
     *  },
     *     runner: array{
     *      id: int,
     *      firstName: string,
     *      lastName: string,
     *      year: int,
     *      gender?: string,
     *     },
     *     time: int,
     *     startingNumber: int,
     *     position: int,
     *     category: string,
     *     categoryPosition: int,
     *     club: string|null,
     *     dnf: bool,
     *     dns: bool
     * }
     */
    public function serialize(IlluminateModel $model): array
    {
        return [
            'id' => $model->id,
            'race' => $this->serializeRace($model->race),
            'runner' => $this->serializeRunner($model->runner),
            'time' => (int)$model->getRawOriginal('time'),
            'startingNumber' => $model->starting_number,
            'position' => $model->position,
            'category' => $model->category,
            'categoryPosition' => $model->category_position,
            'club' => $model->club,
            'dnf' => (bool)$model->DNF,
            'dns' => (bool)$model->DNS,
        ];
    }

    /**
     * @param Race $race
     * @return array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     tag: string|null,
     *     date: int|null,
     *     time: string|null,
     *     vintage: int|null,
     *     distance: int|null,
     *     location: string|null,
     *     surface: string|null,
     *     type: string|null,
     *     region: string|null,
     * }
     */
    private function serializeRace(Race $race): array
    {
        return [
            'id' => $race->id,
            'name' => $race->name,
            'slug' => $race->slug,
            'tag' => $race->tag,
            'date' => $race->date?->getTimestamp(),
            'time' => $race->time?->format('H:i'),
            'vintage' => $race->vintage,
            'distance' => $race->getRawOriginal('distance'),
            'location' => $race->location,
            'surface' => $race->surface,
            'type' => $race->type,
            'region' => $race->region,
        ];
    }

    /**
     * @param Runner $runner
     * @return array{
     *     id: int,
     *     firstName: string,
     *     lastName: string,
     *     year: int,
     *     gender?: string,
     * }
     */
    private function serializeRunner(Runner $runner): array
    {
        return [
            'id' => $runner->id,
            'firstName' => $runner->first_name,
            'lastName' => $runner->last_name,
            'year' => $runner->year,
            'gender' => $runner->gender ?? '',
        ];
    }
}
