<?php

namespace App\Casts;

use App\Models\IlluminateModel;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DistanceCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes = []): string
    {
        if ($value < 1000) {
            return sprintf('%.2f m', $value);
        }

        return sprintf('%.2f km', $value / 1000);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes = []): string
    {
        return $value ?? 0;
    }

    public function getValue(?int $getDistance): string
    {
        if ($getDistance === null) {
            return '';
        }

        return $this->get(new IlluminateModel(), 'distance', $getDistance);
    }
}
