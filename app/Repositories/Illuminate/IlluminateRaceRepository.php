<?php

declare(strict_types=1);


namespace App\Repositories\Illuminate;

use App\Models\Illuminate\Race;
use App\Models\Illuminate\Runner;
use App\Queries\Illuminate\Race\IlluminateSearchRaceQuery;
use App\Queries\Race\RaceSearch;
use App\Repositories\IlluminateRaceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class IlluminateRaceRepository implements IlluminateRaceRepositoryInterface
{
    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return Race::query()->distinct('tag')->pluck('tag')->toArray();
    }

    public function search(IlluminateSearchRaceQuery $raceSearch): LengthAwarePaginator
    {
        $query = Runner::query();

        if ($raceSearch->wihtoutParent) {
            $query->whereNotNull('parent_id');
        }

        return $query
            ->withCount('results')
            ->when($raceSearch->search !== '', function ($query) use ($raceSearch) {
                return $query->where('first_name', 'REGEXP', $raceSearch->search)
                    ->orWhere('last_name', 'REGEXP', $raceSearch->search);
            })
            ->orderBy($raceSearch->sortBy, $raceSearch->sortDirection)
            ->paginate(perPage: $raceSearch->perPage, page: $raceSearch->page);
    }
}
