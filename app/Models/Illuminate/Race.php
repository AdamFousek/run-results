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
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;


/**
 * App\Models\Illuminate\Race
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $slug
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $date
 * @property \Illuminate\Support\Carbon|null $time
 * @property int|null $vintage
 * @property string|null $location
 * @property string $region
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $distance
 * @property string|null $surface
 * @property string|null $type
 * @property string|null $tag
 * @property int $is_parent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Race> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Illuminate\UploadedFiles> $files
 * @property-read int|null $files_count
 * @property-read Race|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Illuminate\Result> $results
 * @property-read int|null $results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Illuminate\Runner> $runners
 * @property-read int|null $runners_count
 * @method static Builder|Race newModelQuery()
 * @method static Builder|Race newQuery()
 * @method static Builder|Race notParents()
 * @method static Builder|Race onlyTrashed()
 * @method static Builder|Race query()
 * @method static Builder|Race whereCreatedAt($value)
 * @method static Builder|Race whereDate($value)
 * @method static Builder|Race whereDeletedAt($value)
 * @method static Builder|Race whereDistance($value)
 * @method static Builder|Race whereId($value)
 * @method static Builder|Race whereIsParent($value)
 * @method static Builder|Race whereLatitude($value)
 * @method static Builder|Race whereLocation($value)
 * @method static Builder|Race whereLongitude($value)
 * @method static Builder|Race whereName($value)
 * @method static Builder|Race whereParentId($value)
 * @method static Builder|Race whereRegion($value)
 * @method static Builder|Race whereSlug($value)
 * @method static Builder|Race whereSurface($value)
 * @method static Builder|Race whereTag($value)
 * @method static Builder|Race whereTime($value)
 * @method static Builder|Race whereType($value)
 * @method static Builder|Race whereUpdatedAt($value)
 * @method static Builder|Race whereVintage($value)
 * @method static Builder|Race withRichText($fields = [])
 * @method static Builder|Race withTrashed()
 * @method static Builder|Race withoutTrashed()
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
        'results',
        'parent',
        'files',
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

    public function files(): MorphMany
    {
        return $this->morphMany(UploadedFiles::class, 'filable');
    }

    public function getSerializer(): ?string
    {
        return \App\Serializer\RaceSerializer::class;
    }
}
