<?php

declare(strict_types=1);


namespace App\Queries\Race;

use App\Repositories\Meilisearch\Results\RaceCollection;
use App\Repositories\RaceRepository;

class RaceSearchHandler
{
    public function __construct(
        private readonly RaceRepository $repository
    ) {
    }

    public function handle(RaceSearch $search): RaceCollection
    {
        return $this->repository->search($search);
    }
}
