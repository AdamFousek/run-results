<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class Posts extends IlluminateModel
{
    use HasFactory, SoftDeletes, Searchable, HasRichText;

    protected array $richTextAttributes = [
        'content',
    ];

    protected array $makeAllSearchableWith = [
        'user',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
