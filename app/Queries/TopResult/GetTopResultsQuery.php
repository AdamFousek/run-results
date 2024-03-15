<?php

declare(strict_types=1);


namespace App\Queries\TopResult;

use App\Models\Illuminate\Enums\RunnerGenderEnum;

readonly class GetTopResultsQuery
{
    public function __construct(
        public string $raceTag,
        public ?RunnerGenderEnum $gender = null,
        public int $limit = 10,
        public int $offset = 0,
        public string $search = '',
    ) {
    }
}
