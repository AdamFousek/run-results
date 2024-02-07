<?php

declare(strict_types=1);


namespace App\Commands\Race;

readonly class CreateRace
{
    public function __construct(
        public string $name,
        public string $description,
        public string $date,
        public string $time,
        public string $location,
        public float $distance,
        public string $surface,
        public string $type,
        public string $tag,
        public ?int $vintage,
        public string $region,
        public ?float $latitude,
        public ?float $longitude,
        public bool $isParent,
        public ?int $parentId,
    ) {
    }
}
