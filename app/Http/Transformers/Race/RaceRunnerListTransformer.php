<?php

declare(strict_types=1);


namespace App\Http\Transformers\Race;

use App\Models\Illuminate\Result;
use Illuminate\Database\Eloquent\Collection;

class RaceRunnerListTransformer
{
    /**
     * @param array<int, ?Result> $results
     * @return array<array<string, mixed>>
     */
    public function transform(array $results): array
    {
        $transformedData = [];
        foreach ($results as $result) {
            if (!$result instanceof Result) {
                continue;
            }

            $transformedData[] = [
                'runner_id' => $result->runner->id,
                'starting_number' => $result->starting_number,
                'first_name' => $result->runner->first_name,
                'last_name' => $result->runner->last_name,
                'gender' => $result->runner->gender,
                'year' => $result->runner->year,
                'club' => $result->club ?? '',
                'position' => $result->position,
                'category_position' => $result->category_position,
                'category' => $result->category,
                'time' => explode('.', $result->time ?? '')[0],
                'DNF' => $result->DNF,
                'DNS' => $result->DNS,
            ];
        }

        return $transformedData;
    }
}
