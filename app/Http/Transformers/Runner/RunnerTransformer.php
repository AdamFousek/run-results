<?php

declare(strict_types=1);


namespace App\Http\Transformers\Runner;

use App\Models\Illuminate\Runner;

class RunnerTransformer
{
    /**
     * @param Runner $runner
     * @return array{
     *      id: int,
     *      firstName: string,
     *      lastName: string,
     *      year: int,
     *      city: string|null,
     *      club: string|null,
     *      results_count: int,
     *      createdAt: string|null
     *  }
     */
    public function transform(Runner $runner): array
    {
        return [
            'id' => $runner->id,
            'firstName' => $runner->first_name,
            'lastName' => $runner->last_name,
            'year' => $runner->year,
            'city' => $runner->city,
            'club' => $runner->club,
            'resultsCount' => $runner->results_count ?? 0,
            'createdAt' => $runner->created_at?->format('j. n. Y H:i:s'),
        ];
    }
}
