<?php

declare(strict_types=1);


namespace App\Queries\Illuminate\Race;

use App\Repositories\IlluminateRaceRepositoryInterface;

class IlluminateSearchRaceHandler
{
    public function __construct(
        private readonly IlluminateRaceRepositoryInterface $repository,
    ) {
    }

    public function handle(IlluminateSearchRaceQuery $query)
    {
        return $this->repository->search($query);
    }
}
