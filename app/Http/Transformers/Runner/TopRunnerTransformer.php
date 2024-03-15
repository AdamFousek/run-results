<?php

declare(strict_types=1);


namespace App\Http\Transformers\Runner;

use App\Models\Meilisearch\Result\TopResult;
use App\Models\QueryResult\TopRunner;
use Illuminate\Support\Collection;

class TopRunnerTransformer
{
    /**
     * @param Collection $topRunners
     * @return array<array{
     *     position: int,
     *     runnerId: int,
     *     name: string,
     *     time: string,
     *     year: int|null,
     *     runnerYear: int,
     * }>
     */
    public function transform(Collection $topRunners): array
    {
        $result = [];
        foreach ($topRunners as $topRunner) {
            if (!$topRunner instanceof TopResult) {
                continue;
            }

            $result[] = [
                'position' => $topRunner->getTopPosition(),
                'runnerId' => $topRunner->getRunner()->getId(),
                'name' => $topRunner->getRunner()->getLastName() . ' ' .$topRunner->getRunner()->getFirstName(),
                'time' => $topRunner->getTime(),
                'year' => $topRunner->getRace()->getDate()?->year,
                'runnerYear' => $topRunner->getRunner()->getYear(),
            ];
        }

        return $result;
    }
}
