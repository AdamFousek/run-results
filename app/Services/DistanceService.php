<?php

declare(strict_types=1);


namespace App\Services;

class DistanceService
{
    public function transform(float $distance): string
    {
        if ($distance < 1000) {
            return sprintf('%.2f m', $distance);
        }

        return sprintf('%.2f km', $distance / 1000);
    }
}
