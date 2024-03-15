<?php

declare(strict_types=1);


namespace App\Commands\TopResult;

use App\Repositories\TopResultRepositoryInterface;

class DeleteByTagHandler
{
    public function __construct(
        private readonly TopResultRepositoryInterface $repository,
    ) {
    }

    public function handle(string $raceTag): void
    {
        $this->repository->deleteByTag($raceTag);
    }
}
