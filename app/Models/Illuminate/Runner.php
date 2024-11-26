<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

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

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes): string => sprintf('%s %s', $attributes['last_name'], $attributes['first_name']),
        );
    }
}
