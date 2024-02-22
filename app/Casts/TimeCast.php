<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TimeCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_int($value)) {
            $seconds = (int)($value / 1000) % 60;
            $minutes = (int)($value / (1000 * 60)) % 60;
            $hours = (int)($value / (1000 * 60 * 60)) % 24;

            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_string($value)) {
            $timeParts = explode(':', $value);
            $value = match (count($timeParts)) {
                1 => (float)$timeParts[0],
                2 => (int)$timeParts[0] * 60 + (float)$timeParts[1],
                3 => (int)$timeParts[0] * 3600 + (int)$timeParts[1] * 60 + (float)$timeParts[2],
                default => 0,
            };

            if ($value === 0) {
                return null;
            }

            return $value * 1000;
        }

        return $value;
    }
}
