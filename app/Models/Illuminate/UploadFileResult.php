<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UploadFileResult extends IlluminateModel
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'total_rows',
        'processed_rows',
        'failed_rows',
        'processed_at',
        'race_id',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'create_at' => 'datetime',
    ];

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }

    public function rows(): HasMany
    {
        return $this->hasMany(UploadFileResultRow::class);
    }
}
