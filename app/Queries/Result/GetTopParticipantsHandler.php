<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Repositories\Illuminate\Results\TopRunnersResult;
use App\Repositories\IlluminateResultRepositoryInterface;

readonly class GetTopParticipantsHandler
{
    public function __construct(
        private IlluminateResultRepositoryInterface $repository,
    ) {
    }

    public function handle(GetTopRunnersBy $query): TopRunnersResult
    {
        return $this->repository->getMostParticipants($query);
    }
}
