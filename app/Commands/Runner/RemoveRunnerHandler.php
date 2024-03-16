<?php

declare(strict_types=1);


namespace App\Commands\Runner;

use App\Repositories\RunnerRepositoryInterface;

readonly class RemoveRunnerHandler
{
    public function __construct(
        private RunnerRepositoryInterface $repository
    ) {
    }

    public function handle(int $id): void
    {
        $this->repository->delete($id);
    }
}
