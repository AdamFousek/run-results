<?php

declare(strict_types=1);


namespace App\Http\Transformers\Runner;

use App\Models\Runner;

class RunnerTransformer
{
    /**
     * @param Runner $runner
     * @return array<string, string|int|float>
     */
    public function transform(Runner $runner): array
    {
        return [
            'id' => $runner->id,
            'first_name' => $runner->first_name,
            'last_name' => $runner->last_name,
            'year' => $runner->year,
            'city' => $runner->city,
            'club' => $runner->club,
        ];
    }
}
