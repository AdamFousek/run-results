<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UplodFileResultRow
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow query()
 * @property int $id
 * @property int $upload_file_result_id
 * @property int $row_number
 * @property string $data
 * @property string $error
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UploadFileResult $uploadFileResult
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow whereRowNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFileResultRow whereUploadFileResultId($value)
 * @mixin \Eloquent
 */
class UploadFileResultRow extends Model
{
    use HasFactory;

    protected $fillable = [
        'upload_file_result_id',
        'row_number',
        'data',
        'error',
    ];

    public function uploadFileResult(): BelongsTo
    {
        return $this->belongsTo(UploadFileResult::class);
    }
}
