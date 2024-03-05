<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate;

use App\Models\Illuminate\Runner;
use App\Repositories\IlluminateRunnerRepositoryInterface;
use Illuminate\Support\Collection;

class IlluminateRunnerRepository implements IlluminateRunnerRepositoryInterface
{

    public function mergerRunner(Runner $source, Runner $target): Runner
    {
        $source->results()->update(['runner_id' => $target->id]);

        $source->delete();

        return $target;
    }

    #[\Override]
    public function findDuplicityByLastName(): Collection
    {
        $sameLastNameAndYear = Runner::query()
            ->select('last_name', 'year')
            ->selectRaw('COUNT(*) as count')
            ->whereNull('deleted_at')
            ->groupBy('last_name', 'year')
            ->having('count', '>', 1)
            ->get();

        $runners = [];
        foreach ($sameLastNameAndYear as $runner) {
            $sameRunners = Runner::query()
                ->where('last_name', $runner->last_name)
                ->where('year', $runner->year)
                ->whereNull('deleted_at')
                ->get();

            $runners[] = $sameRunners;
        }

        return collect($runners);
    }

    public function findDuplicityByFullName(): Collection
    {
        $sameLastNameAndYear = Runner::query()
            ->select('last_name', 'first_name')
            ->selectRaw('COUNT(*) as count')
            ->whereNull('deleted_at')
            ->groupBy('last_name', 'first_name')
            ->having('count', '>', 1)
            ->get();

        $runners = [];
        foreach ($sameLastNameAndYear as $runner) {
            $runners[] = Runner::query()
                ->where('last_name', $runner->last_name)
                ->where('first_name', $runner->first_name)
                ->whereNull('deleted_at')
                ->get();
        }

        return collect($runners);
    }
}
