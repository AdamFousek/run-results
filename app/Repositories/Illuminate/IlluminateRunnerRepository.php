<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate;

use App\Models\Illuminate\Runner;
use App\Repositories\IlluminateRunnerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
            ->select('last_name')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('last_name', 'year')
            ->having('count', '>', 1)
            ->get();

        return Runner::query()
            ->whereIn('last_name', $sameLastNameAndYear->pluck('last_name'))
            ->orderBy('last_name')
            ->get();
    }
}
