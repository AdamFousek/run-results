<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Illuminate\UploadFileResult
 *
 * @property int $id
 * @property int $race_id
 * @property string $file_path
 * @property int $total_rows
 * @property int $processed_rows
 * @property int $failed_rows
 * @property \Illuminate\Support\Carbon|null $processed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Illuminate\Race $race
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Illuminate\UploadFileResultRow> $rows
 * @property-read int|null $rows_count
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult whereFailedRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult whereProcessedRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult whereRaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult whereTotalRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
