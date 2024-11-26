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
    protected array $richTextAttributes = [
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
