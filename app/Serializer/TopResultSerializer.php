<?php

declare(strict_types=1);


namespace App\Serializer;

use App\Models\Illuminate\Race;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;
use App\Models\IlluminateModel;
use Carbon\Carbon;

class TopResultSerializer implements ShouldSerialize
{
    /**
     * @param Result $model
     * @return array{
     *     id: int,
     *     topPosition: int,
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
     * }
     */
    public function serialize(IlluminateModel $model): array
    {
        return [
            'id' => $model->id,
            'topPosition' => $model->getTopPosition(),
            'race' => $this->serializeRace($model->race),
            'runner' => $this->serializeRunner($model->runner),
            'time' => (int)$model->getRawOriginal('time'),
            'lastUpsertedAt' => (new Carbon())->timestamp,
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
