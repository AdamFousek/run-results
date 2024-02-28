<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Models\Illuminate\Race;

readonly class GetResultsQuery
{
    /**
     * @param Race $race
     * @param string $search
     * @param int $page
     * @param bool $showFemale
     * @param bool $showMale
     * @param string[] $categories
     */
    public function __construct(
        public Race $race,
        public string $search = '',
        public int $page = 1,
        public bool $showFemale = false,
        public bool $showMale = false,
        public array $categories = [],
    ) {
    }
}
