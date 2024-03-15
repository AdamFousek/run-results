<?php

declare(strict_types=1);


namespace App\Repositories;

interface IlluminateRaceRepositoryInterface
{
    /**
     * @return array<string>
     */
    public function getTags(): array;
}
