<?php

declare(strict_types=1);


namespace App\Queries\Result;

use App\Models\Illuminate\Enums\RunnerGenderEnum;

readonly class GetTopRunnersBy
{
    public function __construct(
        public string $raceTag,
        public ?RunnerGenderEnum $gender = null,
        public int $limit = 10,
        public bool $isParticipation = false,
    ) {
    }
}
