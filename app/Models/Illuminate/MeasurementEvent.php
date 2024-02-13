<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasurementEvent extends IlluminateModel
{
    use HasFactory;

    protected $guarded = [];

    public function scopeWithoutRobots(Builder $query): void
    {
        $query->where('useragent', 'not like', '%bot%')
            ->where('useragent', 'not like', '%python-requests%')
            ->where('useragent', 'not like', '%http%')
            ->where('useragent', 'not like', '%node-fetch%')
            ->where('useragent', 'not like', '%postman%')
            ->where('useragent', 'not like', '%curl%');
    }
}
