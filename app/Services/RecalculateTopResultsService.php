<?php

declare(strict_types=1);


namespace App\Services;

use App\Commands\TopResult\DeleteByTagHandler;
use App\Commands\TopResult\UpsertTopResultHandler;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Illuminate\Result;
use App\Queries\Result\GetTopRunnersBy;
use App\Queries\Result\GetTopRunnersByQuery;

readonly class RecalculateTopResultsService
{
    public function __construct(
        private GetTopRunnersByQuery $getTopRunnersByQuery,
        private UpsertTopResultHandler $upsertTopResultHandler,
        private DeleteByTagHandler $deleteByTagHandler,
    ) {
    }

    public function handle(string $raceTag): int
    {
        $processed = 0;
        $this->deleteByTagHandler->handle($raceTag);

        foreach ([RunnerGenderEnum::FEMALE, RunnerGenderEnum::MALE] as $gender) {
            $index = 0;
            do {
                $results = $this->getTopRunnersByQuery->handle(new GetTopRunnersBy(
                    raceTag: $raceTag,
                    gender: $gender,
                    limit: 100,
                    offset: $index * 100,
                ));

                $results = $results->map(function (Result $result, int $key) use ($index) {
                    $result->setTopPosition(($key + 1) + ($index * 100));
                    return $result;
                });

                $this->upsertTopResultHandler->handle($results);

                $processed += $results->count();

                $index++;
            } while ($results->count() > 0);
        }

        return $processed;
    }
}
