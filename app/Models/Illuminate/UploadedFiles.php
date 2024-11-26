<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UploadedFiles extends IlluminateModel
{
    protected $fillable = [
        'name',
        'file_path',
        'downloaded',
        'is_public',
        'created_at',
        'updated_at',
    ];

    public function filable(): MorphTo
    {
        return $this->morphTo();
    }
}
