<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Models\Illuminate\Runner;

interface IlluminateRunnerRepositoryInterface
{
    public function mergerRunner(Runner $source, Runner $target): Runner;
}
