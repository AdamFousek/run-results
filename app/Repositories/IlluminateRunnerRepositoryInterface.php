<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Models\Illuminate\Runner;
use Illuminate\Support\Collection;

interface IlluminateRunnerRepositoryInterface
{
    public function mergerRunner(Runner $source, Runner $target): Runner;

    /**
     * @return Collection
     */
    public function findDuplicityByLastName(): Collection;

    /**
     * @return Collection
     */
    public function findDuplicityByFullName(): Collection;
}
