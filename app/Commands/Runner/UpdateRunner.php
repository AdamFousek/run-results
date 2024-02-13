<?php

declare(strict_types=1);


namespace App\Commands\Runner;

use App\Models\Illuminate\Runner;

class UpdateRunner
{
    public function __construct(
        public Runner $runner,
        public string $firstName,
        public string $lastName,
        public ?int $day,
        public ?int $month,
        public int $year,
        public ?string $city,
        public ?string $club,
    )
    {

    }
}
