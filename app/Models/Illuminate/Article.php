<?php

namespace App\Models\Illuminate;

use App\Models\IlluminateModel;
use App\Serializer\ArticleSerializer;
use App\Values\ArticleMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;
use Tonysm\RichTextLaravel\Models\Traits\HasRichText;

class Article extends IlluminateModel
{
    use HasFactory, Searchable, HasRichText, HasTags;

    protected $guarded = [];

    protected array $richTextAttributes = [
        'content',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'meta' => ArticleMeta::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published_at', '<=', now());
    }

    public function getSerializer(): ?string
    {
        return ArticleSerializer::class;
    }
}
