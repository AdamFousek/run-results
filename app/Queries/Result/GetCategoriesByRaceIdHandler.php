<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Repositories\IlluminateResultRepositoryInterface;

readonly class GetCategoriesByRaceIdHandler
{
    public function __construct(
        private IlluminateResultRepositoryInterface $resultRepository,
    ) {
    }

    /**
     * @param GetCategoriesByRaceIdQuery $query
     * @return string[]
     */
    public function handle(GetCategoriesByRaceIdQuery $query): array
    {
        return $this->resultRepository->getCategoriesByRaceId($query->raceId);
    }
}
