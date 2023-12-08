<?php

namespace App\Models;

use App\Casts\DistanceCast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;


/**
 * App\Models\Race
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon $date
 * @property string $location
 * @property mixed $distance
 * @property string $surface
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Race|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Race> $records
 * @property-read int|null $records_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Result> $results
 * @property-read int|null $results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Runner> $runners
 * @property-read int|null $runners_count
 * @method static \Database\Factories\RaceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Race newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Race newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Race onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Race query()
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereSurface($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Race withoutTrashed()
 * @mixin \Eloquent
 */
class Race extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'date',
        'location',
        'distance',
        'surface',
        'type',
    ];

    protected $casts = [
        'date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'distance' => DistanceCast::class,
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function runners(): BelongsToMany
    {
        return $this->belongsToMany(Runner::class, 'results')
            ->withTimestamps();
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date->timestamp,
            'location' => $this->location,
            'distance' => $this->getRawOriginal('distance'),
            'surface' => $this->surface,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'upserted_at' => new Carbon(),
        ];
    }
}
