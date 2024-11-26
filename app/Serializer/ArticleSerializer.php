<?php

declare(strict_types=1);


namespace App\Serializer;

use App\Enum\TagType;
use App\Models\Illuminate\Article;
use App\Models\IlluminateModel;

class ArticleSerializer implements ShouldSerialize
{
    /**
     * @param Article $model
     * @return array{
     *     id: int,
     *     slug: string,
     *     title: string,
     *     content: string,
     *     publishedAt: int|null,
     *     createdAt: int|null,
     *     updatedAt: int|null,
     *     updatedAt: int,
     * }
     */
    public function serialize(IlluminateModel $model): array
    {
        if (!$model instanceof Article) {
            throw new \InvalidArgumentException('Expected an Article model');
        }

        $model->load('user');
        $tags = $model->tags()->withType(TagType::ARTICLE_TAG->value)->get();
        $keywords = $model->tags()->withType(TagType::ARTICLE_KEYWORDS->value)->get();

        return [
            'id' => $model->id,
            'slug' => $model->slug,
            'title' => $model->title,
            'content' => $model->content->toPlainText(),
            'publishedAt' => $model->published_at?->getTimestamp(),
            'author' => $model->user->username,
            'createdAt' => $model->created_at?->getTimestamp(),
            'updatedAt' => $model->updated_at?->getTimestamp(),
            'upsertedAt' => now()->getTimestamp(),
            'tags' => $tags->pluck('name')->toArray(),
            'metaDescription' => $model->meta->description,
            'metaKeywords' => $keywords->pluck('name')->toArray(),
        ];
    }
}
