<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Queries\Race\GetRaceIdsBySearch;
use App\Queries\Race\RaceSearch;
use App\Repositories\Meilisearch\Results\RaceCollection;

interface RaceRepositoryInterface
{
    public function search(RaceSearch $query): RaceCollection;

    /**
     * @param GetRaceIdsBySearch $search
     * @return int[]
     */
    public function getIds(GetRaceIdsBySearch $search): array;

    public function delete(int $id): void;
}
