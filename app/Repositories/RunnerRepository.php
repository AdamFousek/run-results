<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Queries\RunnerSearch;
use App\Repositories\Meilisearch\Results\RunnerCollection;

interface RunnerRepository
{
    public function search(RunnerSearch $query): RunnerCollection;
}
