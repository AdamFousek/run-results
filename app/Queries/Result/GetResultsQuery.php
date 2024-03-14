<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Models\Illuminate\Race;
use App\Models\Illuminate\Runner;
use App\Services\ResultSortService;

readonly class GetResultsQuery
{
    /**
     * @param Runner|null $runner
     * @param Race|null $race
     * @param string $search
     * @param int $limit
     * @param int $offset
     * @param string $sort
     * @param bool $showFemale
     * @param bool $showMale
     * @param string[] $categories
     */
    public function __construct(
        public ?Runner $runner = null,
        public ?Race $race = null,
        public string $search = '',
        public int $limit = 10,
        public int $offset = 0,
        public string $sort = ResultSortService::DEFAULT_SORT,
        public bool $showFemale = true,
        public bool $showMale = true,
        public array $categories = [],
    ) {
    }
}
