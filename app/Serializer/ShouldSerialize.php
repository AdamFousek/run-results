<?php

declare(strict_types=1);


namespace App\Serializer;

use App\Models\IlluminateModel;

interface ShouldSerialize
{
    public function serialize(IlluminateModel $model): array;
}
