<?php

namespace App\Models;

use App\Serializer\TopResultSerializer;
use Laravel\Scout\Searchable;

class TopResult extends IlluminateModel
{
    use Searchable;

    public function getSerializer(): ?string
    {
        return TopResultSerializer::class;
    }
}
