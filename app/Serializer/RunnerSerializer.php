<?php

declare(strict_types=1);


namespace App\Serializer;

use App\Models\Illuminate\Runner;

class RunnerSerializer
{
    /**
     * @param Runner $runner
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
    public function serialize(Runner $runner): array
    {
        return [
            'id' => $runner->id,
            'userId' => $runner->user_id,
            'firstName' => $runner->first_name,
            'lastName' => $runner->last_name,
            'year' => $runner->year,
            'city' => $runner->city,
            'club' => $runner->club,
            'gender' => $runner->gender,
            'resultsCount' => $runner->results->count(),
            'createdAt' => $runner->created_at?->getTimestamp(),
            'updatedAt' => $runner->updated_at?->getTimestamp(),
            'upsertedAt' => now()->getTimestamp(),
        ];
    }
}
