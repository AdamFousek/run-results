<?php

declare(strict_types=1);


namespace App\Http\Transformers\Meilisearch;

use App\Models\Meilisearch\Article;
use Illuminate\Support\Str;

class ArticleListTransformer
{
    /**
     * @param Article[] $collection
     * @return array<array{
     *     id: int,
     *     title: string,
     *     content: string,
     *     author: string,
     *     publishedAt: string|null,
     *     createdAt: string|null,
     *     updatedAt: string|null,
     *     tags: string[],
     *     metaDescription: string,
     *     metaKeywords: string[],
     *  }>
     */
    public function transform(array $collection): array
    {
        $result = [];
        foreach ($collection as $article) {
            $result[] = [
                'id' => $article->getId(),
                'slug' => $article->getSlug(),
                'title' => $article->getTitle(),
                'content' => Str::words($article->getContent()),
                'author' => $article->getAuthor(),
                'publishedAt' => $article->getPublishedAt()?->format('j. n. Y H:i:s'),
                'createdAt' => $article->getCreatedAt()?->format('j. n. Y H:i:s'),
                'updatedAt' => $article->getUpdatedAt()?->format('j. n. Y H:i:s'),
                'tags' => $article->getTags(),
                'metaDescription' => $article->getMetaDescription(),
                'metaKeywords' => $article->getMetaKeywords(),
            ];
        }

        return $result;
    }
}
