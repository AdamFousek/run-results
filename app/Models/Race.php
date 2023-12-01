<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

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
        'date' => 'datetime:d.m.Y H:i:s',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
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
            'date' => $this->date,
            'location' => $this->location,
            'distance' => $this->distance,
            'surface' => $this->surface,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'upserted_at' => new Carbon(),
        ];
    }
}
