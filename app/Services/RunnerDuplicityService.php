<?php

declare(strict_types=1);


namespace App\Services;

use App\Models\Illuminate\Runner;

class RunnerDuplicityService
{
    /**
     * @return Runner[]
     */
    public function provideDuplicities(): array
    {
        $runnerGroupedLastName = Runner::query()
            ->select('last_name')
            ->groupBy('last_name', 'year')
            ->havingRaw('count(*) > 1')
            ->get();

        $runners =
    }
}
