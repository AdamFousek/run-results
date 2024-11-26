<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadFileResultRow extends IlluminateModel
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
