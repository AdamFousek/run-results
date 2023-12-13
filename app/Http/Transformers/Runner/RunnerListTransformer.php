<?php

declare(strict_types=1);


namespace App\Http\Transformers\Runner;

use App\Models\Runner;
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
                'first_name' => $runner->first_name,
                'last_name' => $runner->last_name,
                'year' => $runner->year,
            ];
        }

        return $result;
    }
}
