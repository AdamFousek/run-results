<?php

declare(strict_types=1);


namespace App\Serializer;

use App\Models\Illuminate\Race;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;

class ResultSerializer
{
    /**
     * @param Result $result
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
    public function serialize(Result $result): array
    {
        return [
            'id' => $result->id,
            'race' => $this->serializeRace($result->race),
            'runner' => $this->serializeRunner($result->runner),
            'time' => (int)$result->getRawOriginal('time'),
            'startingNumber' => $result->starting_number,
            'position' => $result->position,
            'category' => $result->category,
            'categoryPosition' => $result->category_position,
            'club' => $result->club,
            'dnf' => (bool)$result->DNF,
            'dns' => (bool)$result->DNS,
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
