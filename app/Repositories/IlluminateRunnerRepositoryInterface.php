<?php

declare(strict_types=1);


namespace App\Repositories;

use App\Models\Illuminate\Runner;
use Illuminate\Database\Eloquent\Collection;

interface IlluminateRunnerRepositoryInterface
{
    public function mergerRunner(Runner $source, Runner $target): Runner;

    /**
     * @return Collection<Runner>
     */
    public function findDuplicityByLastName(): Collection;
}
