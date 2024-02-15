<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Illuminate\MeasurementEvent
 *
 * @property int $id
 * @property string $type
 * @property string $attribute
 * @property string $useragent
 * @property string $visitorid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|MeasurementEvent newModelQuery()
 * @method static Builder|MeasurementEvent newQuery()
 * @method static Builder|MeasurementEvent query()
 * @method static Builder|MeasurementEvent whereAttribute($value)
 * @method static Builder|MeasurementEvent whereCreatedAt($value)
 * @method static Builder|MeasurementEvent whereId($value)
 * @method static Builder|MeasurementEvent whereType($value)
 * @method static Builder|MeasurementEvent whereUpdatedAt($value)
 * @method static Builder|MeasurementEvent whereUseragent($value)
 * @method static Builder|MeasurementEvent whereVisitorid($value)
 * @method static Builder|MeasurementEvent withoutRobots()
 * @mixin \Eloquent
 */
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
