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
     *      date: int|null,
     *      time: string|null,
     *     },
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
     *     date: int|null,
     *     time: string|null,
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
