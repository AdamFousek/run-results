<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Repositories\IlluminateResultRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetResultsHandler
{
    public function __construct(
        private readonly IlluminateResultRepositoryInterface $resultRepository,
    ) {
    }

    public function handle(GetResultsQuery $query): LengthAwarePaginator
    {
        return $this->resultRepository->findResults($query);
    }
}
