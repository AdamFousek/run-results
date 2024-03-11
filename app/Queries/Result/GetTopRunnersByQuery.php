<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Models\Illuminate\Result;
use App\Repositories\IlluminateResultRepositoryInterface;
use Illuminate\Support\Collection;

readonly class GetTopRunnersByQuery
{
    public function __construct(
        private IlluminateResultRepositoryInterface $resultRepository,
    ) {
    }

    /**
     * @param GetTopRunnersBy $query
     * @return Collection<Result>
     */
    public function handle(GetTopRunnersBy $query): Collection
    {
        return $this->resultRepository->getTopRunnersBy($query);
    }
}
