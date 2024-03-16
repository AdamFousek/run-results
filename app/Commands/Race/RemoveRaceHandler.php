<?php

declare(strict_types=1);


namespace App\Commands\Race;

use App\Repositories\RaceRepositoryInterface;

readonly class RemoveRaceHandler
{
    public function __construct(
        private RaceRepositoryInterface $repository
    ) {
    }

    public function handle(int $id): void
    {
        $this->repository->delete($id);
    }
}
