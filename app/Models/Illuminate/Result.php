<?php

namespace App\Models\Illuminate;

use App\Casts\TimeCast;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Result extends IlluminateModel
{
    use HasFactory, Searchable;

    private int $topPosition;

    protected $fillable = [
        'runner_id',
        'race_id',
        'starting_number',
        'time',
        'position',
        'category',
        'category_position',
        'club',
        'DNF',
        'DNS',
    ];

    protected $casts = [
        'time' => TimeCast::class,
    ];

    protected array $makeAllSearchableWith = [
        'runner',
        'race',
    ];

    public function runner(): BelongsTo
    {
        return $this->belongsTo(Runner::class);
    }

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function scopeWithoutFemale(Builder $query): Builder
    {
        return $query->whereHas('runner', function (Builder $query) {
            $query->where('gender', '!=', RunnerGenderEnum::FEMALE);
        });
    }

    public function scopeWithoutMale(Builder $query): Builder
    {
        return $query->whereHas('runner', function (Builder $query) {
            $query->where('gender', '!=', RunnerGenderEnum::MALE);
        });
    }

    public function getSerializer(): ?string
    {
        return \App\Serializer\ResultSerializer::class;
    }

    public function getTopPosition(): int
    {
        return $this->topPosition;
    }

    public function setTopPosition(int $topPosition): void
    {
        $this->topPosition = $topPosition;
    }
}
