<?php

declare(strict_types=1);


namespace App\Queries\Runner;

use App\Repositories\IlluminateRunnerRepositoryInterface;
use Illuminate\Support\Collection;

readonly class GetRunnerDuplicityByFullNameQuery
{
    public function __construct(
        private IlluminateRunnerRepositoryInterface $repository,
    ) {
    }

    /**
     * @return Collection
     */
    public function handle(): Collection
    {
        return $this->repository->findDuplicityByFullName();
    }
}
