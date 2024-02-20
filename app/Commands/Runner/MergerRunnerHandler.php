<?php

declare(strict_types=1);


namespace App\Commands\Runner;

use App\Models\Illuminate\Runner;
use App\Repositories\IlluminateRunnerRepositoryInterface;

class MergerRunnerHandler
{
    public function __construct(
        private readonly IlluminateRunnerRepositoryInterface $repository,
    ) {
    }

    public function handle(MergerRunner $command): Runner
    {
        return $this->repository->mergerRunner($command->source, $command->target);
    }
}
