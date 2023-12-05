<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/**
 * App\Models\Runner
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $first_name
 * @property string $last_name
 * @property int|null $day
 * @property int|null $month
 * @property int $year
 * @property string|null $city
 * @property string|null $club
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\RunnerFactory factory($count = null, $state = [])
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
class Runner extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'day',
        'month',
        'year',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'year' => $this->year,
            'city' => $this->city,
            'club' => $this->club,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'upserted_at' => new Carbon(),
        ];
    }
}
