<?php

declare(strict_types=1);


namespace App\Http\Transformers\Runner;

use App\Models\QueryResult\TopRunner;

class TopParticipantTransformer
{
    /**
     * @param TopRunner[] $topRunners
     * @return array<array{
     *     position: int,
     *     runnerId: int,
     *     name: string,
     *     time: string,
     *     year: int,
     *     runnerYear: int,
     *     participantCount: int,
     * }>
     */
    public function transform(array $topRunners): array
    {
        $result = [];
        foreach ($topRunners as $topRunner) {
            $result[] = [
                'position' => $topRunner->position,
                'runnerId' => $topRunner->runnerId,
                'name' => $topRunner->name,
                'time' => $topRunner->time,
                'year' => $topRunner->year,
                'runnerYear' => $topRunner->runnerYear,
                'participantCount' => $topRunner->participiantCount,
            ];
        }

        return $result;
    }
}
