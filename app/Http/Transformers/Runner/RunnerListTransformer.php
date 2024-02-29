<?php

declare(strict_types=1);


namespace App\Http\Transformers\Runner;

use App\Models\Illuminate\Runner;
use Illuminate\Database\Eloquent\Collection;

class RunnerListTransformer
{
    /**
     * @param Collection $runners
     * @return array<array<string, mixed>>
     */
    public function transform(Collection $runners): array
    {
        $result = [];
        foreach ($runners as $runner) {
            if (!$runner instanceof Runner) {
                continue;
            }

            $result[] = [
                'id' => $runner->id,
                'firstName' => $runner->first_name,
                'lastName' => $runner->last_name,
                'year' => $runner->year,
            ];
        }

        return $result;
    }
}
