<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Illuminate\UploadedFiles
 *
 * @property int $id
 * @property string $name
 * @property string $file_path
 * @property int $is_public
 * @property int $downloaded
 * @property int $filable_id
 * @property string $filable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $filable
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles query()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles whereDownloaded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles whereFilableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles whereFilableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFiles whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UploadedFiles extends IlluminateModel
{
    use HasFactory;

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
