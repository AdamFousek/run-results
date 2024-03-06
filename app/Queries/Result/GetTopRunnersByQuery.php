<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Repositories\Illuminate\Results\TopRunnersResult;
use App\Repositories\IlluminateResultRepositoryInterface;

readonly class GetTopRunnersByQuery
{
    public function __construct(
        private IlluminateResultRepositoryInterface $resultRepository,
    ) {
    }

    public function handle(GetTopRunnersBy $query): TopRunnersResult
    {
        return $this->resultRepository->getTopRunnersBy($query);
    }
}
