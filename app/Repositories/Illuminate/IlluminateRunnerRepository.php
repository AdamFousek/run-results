<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate;

use App\Models\Illuminate\Runner;
use App\Repositories\IlluminateRunnerRepositoryInterface;

class IlluminateRunnerRepository implements IlluminateRunnerRepositoryInterface
{

    public function mergerRunner(Runner $source, Runner $target): Runner
    {
        $source->results()->update(['runner_id' => $target->id]);

        $source->delete();

        return $target;
    }
}
