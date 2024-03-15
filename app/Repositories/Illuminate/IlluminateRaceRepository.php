<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate;

use App\Models\Illuminate\Race;
use App\Repositories\IlluminateRaceRepositoryInterface;

class IlluminateRaceRepository implements IlluminateRaceRepositoryInterface
{
    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return Race::query()->distinct('tag')->pluck('tag')->toArray();
    }
}
