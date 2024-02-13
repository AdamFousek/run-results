<?php

namespace App\Models\Illuminate;

use App\Casts\DistanceCast;
use App\Models\IlluminateModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;


/**
 * App\Models\Illuminate\Race
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property mixed $description
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Illuminate\Result> $results
 * @property-read int|null $results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Illuminate\Runner> $runners
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
 * @property string $slug
 * @property int $is_parent
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereIsParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Race withRichText($fields = [])
 * @property \Illuminate\Support\Carbon|null $time
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Race> $children
 * @property-read int|null $children_count
 * @method static \Illuminate\Database\Eloquent\Builder|Race whereTime($value)
 * @mixin \Eloquent
 */
class Race extends IlluminateModel
{
    use HasFactory, SoftDeletes, Searchable, HasRichText;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'date',
        'time',
        'location',
        'distance',
        'surface',
        'is_parent',
        'type',
        'tag',
        'region',
        'vintage',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'distance' => DistanceCast::class,
    ];

    /**
     * @var array|string[]
     */
    protected array $richTextFields = [
        'description',
    ];

    protected array $makeAllSearchableWith = [
        'results'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
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

    public function scopeNotParents(Builder $query): Builder
    {
        return $query->whereNot('is_parent', true);
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date?->timestamp,
            'location' => $this->location,
            'distance' => $this->getRawOriginal('distance'),
            'surface' => $this->surface,
            'runnerCount' => $this->results->count(),
            'type' => $this->type,
            'is_parent' => $this->is_parent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'upserted_at' => new Carbon(),
        ];
    }
}
