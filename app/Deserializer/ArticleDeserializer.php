<?php

declare(strict_types=1);


namespace App\Deserializer;

use App\Models\Meilisearch\Article;
use Illuminate\Support\Carbon;

class ArticleDeserializer
{
    /**
     * @param array{
     *     id: int,
     *     slug: string,
     *     title: string,
     *     content: string,
     *     author: string,
     *     publishedAt: ?int,
     *     tags: string[],
     *     metaDescription: string,
     *     metaKeywords: string[],
     *     createdAt: ?int,
     *     updatedAt: ?int,
     * } $data
     * @return Article
     */
    public function deserialize(array $data): Article
    {
        $article = new Article();
        $article->setId($data['id']);
        $article->setSlug($data['slug']);
        $article->setTitle($data['title']);
        $article->setContent($data['content']);
        $article->setAuthor($data['author']);
        $article->setPublishedAt($data['publishedAt'] ? Carbon::createFromTimestamp($data['publishedAt']) : null);
        $article->setCreatedAt($data['createdAt'] ? Carbon::createFromTimestamp($data['createdAt']) : null);
        $article->setUpdatedAt($data['updatedAt'] ? Carbon::createFromTimestamp($data['updatedAt']) : null);
        $article->setTags($data['tags']);
        $article->setMetaDescription($data['metaDescription']);
        $article->setMetaKeywords($data['metaKeywords']);

        return $article;
    }
}
