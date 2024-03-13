<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Models\Illuminate\Runner;
use App\Services\ResultSortService;

readonly class GetRunnerResultsQuery
{
    public function __construct(
        public ?Runner $runner = null,
        public string $search = '',
        public int $limit = 10,
        public int $offset = 0,
        public string $sort = ResultSortService::DEFAULT_SORT,
    ) {
    }
}
