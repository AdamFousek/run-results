<?php

declare(strict_types=1);


namespace App\Services\Providers;

use App\Models\Illuminate\Runner;
use App\Queries\Runner\GetRunnerDuplicityByFullNameQuery;
use App\Queries\Runner\GetRunnerDuplicityByLastNameQuery;
use Illuminate\Database\Eloquent\Collection;

readonly class DuplicityRunnersService
{
    private const int PERCENTAGE_LIMIT = 30;
    private const int YEAR_DIFFERENCE = 20;

    public function __construct(
        private GetRunnerDuplicityByLastNameQuery $getRunnerDuplicityByLastNameQuery,
        private GetRunnerDuplicityByFullNameQuery $getRunnerDuplicityByFullNameQuery,
    ) {
    }

    public function provide(): Collection
    {
        $runners = $this->getRunnerDuplicityByLastNameQuery->handle();
        $sameNameRunners = $this->getRunnerDuplicityByFullNameQuery->handle();

        $result = [];
        foreach ($runners as $groupedRunners) {
            if ($groupedRunners->count() !== 2) {
                continue;
            }

            $lastNames = $groupedRunners->pluck('first_name')->toArray();
            $firstLastName = (string)(array_shift($lastNames));
            $secondLastName = (string)(array_shift($lastNames));

            $percentage = 0;
            similar_text($firstLastName, $secondLastName, $percentage);

            if ($percentage > self::PERCENTAGE_LIMIT) {
                $result[] = ['percentage' => $percentage, 'runners' => $groupedRunners];
            }
        }

        foreach ($sameNameRunners as $sameNameRunner) {
            if ($sameNameRunner->count() !== 2) {
                continue;
            }

            $years = $sameNameRunner->pluck('year')->toArray();
            $firstYear = (int)(array_shift($years));
            $secondYear = (int)(array_shift($years));
            $diff = abs($firstYear - $secondYear);

            if ($diff <= self::YEAR_DIFFERENCE) {
                $percentage = $diff > 0 ? 100 - ($diff * 100 / self::YEAR_DIFFERENCE) : 100;
                $result[] = ['percentage' => (int)$percentage, 'runners' => $sameNameRunner];
            }
        }

        usort($result, static function (array $a, array $b): int {
            return $b['percentage'] <=> $a['percentage'];
        });

        $collection = new Collection();
        foreach ($result as $item) {
            $collection = $collection->merge($item['runners']);
        }

        return $collection;
    }
}
