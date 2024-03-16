<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Queries\Runner\RunnerSearch;
use App\Repositories\Meilisearch\Results\RunnerCollection;

interface RunnerRepositoryInterface
{
    public function search(RunnerSearch $query): RunnerCollection;

    public function searchByNameAndYear(string $lastName, string $firstName, int $year): RunnerCollection;

    public function delete(int $id): void;
}
