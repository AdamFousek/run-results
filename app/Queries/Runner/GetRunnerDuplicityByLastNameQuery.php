<?php

declare(strict_types=1);


namespace App\Queries\Runner;

use App\Models\Illuminate\Runner;
use App\Repositories\IlluminateRunnerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

readonly class GetRunnerDuplicityByLastNameQuery
{
    public function __construct(
        private IlluminateRunnerRepositoryInterface $repository,
    ) {
    }

    /**
     * @return Collection<Runner>
     */
    public function handle(): Collection
    {
        return $this->repository->findDuplicityByLastName();
    }
}
