<?php

declare(strict_types=1);


namespace App\Http\Transformers\Runner;

use App\Models\QueryResult\TopRunner;

class TopRunnerTransformer
{
    /**
     * @param TopRunner[] $topRunners
     * @return array<array{
     *     runnerId: int,
     *     name: string,
     *     time: string,
     *     year: int,
     *     participiantCount: int,
     * }>
     */
    public function transform(array $topRunners): array
    {
        $result = [];
        foreach ($topRunners as $topRunner) {
            $result[] = [
                'runnerId' => $topRunner->runnerId,
                'name' => $topRunner->name,
                'time' => $topRunner->time,
                'year' => $topRunner->year,
                'participiantCount' => $topRunner->participiantCount,
            ];
        }

        return $result;
    }
}
