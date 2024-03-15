<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Queries\TopResult\GetTopResultsQuery;
use App\Repositories\Meilisearch\Results\TopResultCollection;
use Illuminate\Support\Collection;

interface TopResultRepositoryInterface
{
    public function upsert(Collection $results): void;

    public function deleteByTag(string $raceTag): void;

    public function find(GetTopResultsQuery $query): TopResultCollection;
}
