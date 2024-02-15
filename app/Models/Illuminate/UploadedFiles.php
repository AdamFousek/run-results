<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UploadedFiles extends IlluminateModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'downloaded',
        'created_at',
        'updated_at',
    ];

    public function filable(): MorphTo
    {
        return $this->morphTo();
    }
}
