<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Queries\Illuminate\Race\IlluminateSearchRaceQuery;

interface IlluminateRaceRepositoryInterface
{
    /**
     * @return array<string>
     */
    public function getTags(): array;

    public function search(IlluminateSearchRaceQuery $raceSearch);
}
