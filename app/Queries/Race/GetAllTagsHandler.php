<?php

declare(strict_types=1);


namespace App\Queries\Race;

use App\Repositories\IlluminateRaceRepositoryInterface;

class GetAllTagsHandler
{
    public function __construct(
        private readonly IlluminateRaceRepositoryInterface $repository,
    ) {
    }

    /**
     * @return string[]
     */
    public function handle(): array
    {
        return $this->repository->getTags();
    }
}
