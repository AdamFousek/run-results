<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * App\Models\Illuminate\Runner
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $day
 * @property string|null $month
 * @property int $year
 * @property string|null $city
 * @property string|null $club
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Illuminate\Race> $races
 * @property-read int|null $races_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Illuminate\Result> $results
 * @property-read int|null $results_count
 * @property-read \App\Models\Illuminate\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Runner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Runner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Runner onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Runner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereClub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner whereYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Runner withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Runner withoutTrashed()
 * @mixin \Eloquent
 */
class Runner extends IlluminateModel
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'day',
        'month',
        'year',
        'gender',
        'city',
        'club',
    ];

    protected $hidden = [
        'user_id',
        'day',
        'month',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected array $makeAllSearchableWith = [
        'results'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function races(): BelongsToMany
    {
        return $this->belongsToMany(Race::class, 'results')
            ->withTimestamps();
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function getSerializer(): ?string
    {
        return \App\Serializer\RunnerSerializer::class;
    }
}
