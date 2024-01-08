<?php

declare(strict_types=1);


namespace App\Commands\Race;

use App\Models\Race;
use Illuminate\Support\Carbon;

readonly class UpdateRace
{
    public function __construct(
        public Race $race,
        public string $name,
        public string $description,
        public ?Carbon $date,
        public string $location,
        public float $distance,
        public string $surface,
        public string $type,
        public bool $isParent,
        public ?int $parentId,
    ) {
    }
}
