<?php

namespace App\Models\Illuminate;

use App\Casts\TimeCast;
use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Illuminate\Result
 *
 * @property int $id
 * @property int $runner_id
 * @property int $race_id
 * @property int $starting_number
 * @property int $position
 * @property mixed $time
 * @property string $category
 * @property int $category_position
 * @property int $DNF
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Illuminate\Race $race
 * @property-read \App\Models\Illuminate\Runner $runner
 * @method static \Database\Factories\ResultFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Result newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Result newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Result query()
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereCategoryPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereDNF($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereRaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereRunnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereStartingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Result extends IlluminateModel
{
    use HasFactory;

    protected $fillable = [
        'runner_id',
        'race_id',
        'starting_number',
        'time',
        'position',
        'category',
        'category_position',
        'DNF',
        'DNS',
    ];

    protected $casts = [
        'time' => TimeCast::class,
    ];

    public function runner(): BelongsTo
    {
        return $this->belongsTo(Runner::class);
    }

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }
}
