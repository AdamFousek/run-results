<?php

declare(strict_types=1);


namespace App\Commands\Runner;

readonly class CreateRunner
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public ?int $day,
        public ?int $month,
        public int $year,
        public ?string $city,
        public ?string $club,
        public string $gender = '',
    ) {
    }
}
