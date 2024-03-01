<?php

declare(strict_types=1);


namespace App\Services\Providers;

use App\Models\Illuminate\Runner;
use App\Queries\Runner\GetRunnerDuplicityByLastNameQuery;
use Illuminate\Database\Eloquent\Collection;

readonly class DuplicityRunnersService
{
    private const int PERCENTAGE_LIMIT = 30;

    public function __construct(
        private GetRunnerDuplicityByLastNameQuery $getRunnerDuplicityByLastNameQuery,
    ) {
    }

    public function provide(): Collection
    {
        $runners = $this->getRunnerDuplicityByLastNameQuery->handle();

        $grouped = $runners->groupBy('last_name');

        $removeFromGroup = [];
        foreach ($grouped as $lastName => $groupedRunners) {
            if ($groupedRunners->count() > 2) {
                continue;
            }

            $lastNames = $groupedRunners->pluck('first_name')->toArray();
            $firstLastName = array_shift($lastNames);
            $secondLastName = array_shift($lastNames);

            $percentage = 0;
            similar_text($firstLastName, $secondLastName, $percentage);

            if ($percentage > self::PERCENTAGE_LIMIT) {
                continue;
            }

            $removeFromGroup[] = $lastName;
        }

        return $runners->filter(function (Runner $runner) use ($removeFromGroup): bool {
            return !in_array($runner->last_name, $removeFromGroup, true);
        });
    }
}
