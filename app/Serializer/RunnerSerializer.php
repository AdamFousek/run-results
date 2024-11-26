<?php

declare(strict_types=1);


namespace App\Serializer;

use App\Models\Illuminate\Runner;
use App\Models\IlluminateModel;

class RunnerSerializer implements ShouldSerialize
{
    /**
     * @param Runner $model
     * @return array{
     *     id: int,
     *     userId: int|null,
     *     firstName: string,
     *     lastName: string,
     *     year: int,
     *     city: string|null,
     *     club: string|null,
     *     createdAt: int|null,
     *     updatedAt: int|null,
     *     upsertedAt: int
     * }
     */
    public function serialize(IlluminateModel $model): array
    {
        return [
            'id' => $model->id,
            'userId' => $model->user_id,
            'firstName' => $model->first_name,
            'lastName' => $model->last_name,
            'year' => $model->year,
            'city' => $model->city,
            'club' => $model->club,
            'gender' => $model->gender,
            'resultsCount' => $model->results->count(),
            'createdAt' => $model->created_at?->getTimestamp(),
            'updatedAt' => $model->updated_at?->getTimestamp(),
            'upsertedAt' => now()->getTimestamp(),
        ];
    }
}
