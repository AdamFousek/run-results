<?php

declare(strict_types=1);


namespace App\Http\Transformers\Article;

use App\Models\Illuminate\Article;

class ArticleListTransformer
{
    /**
     * @param Article[] $articles
     * @return array<array{
     *     id: int,
     *      slug: string,
     *      title: string,
     *      content: string,
     *      publishedAt: string|null,
     *      createdAt: string|null,
     *      updatedAt: string|null,
     *      author: string
     *  }>
     */
    public function transform(array $articles): array
    {
        $transformedArticles = [];
        foreach ($articles as $article) {
            $transformedArticles[] = $this->transformOne($article);
        }

        return $transformedArticles;
    }

    /**
     * @param Article $article
     * @return array{
     *     id: int,
     *     slug: string,
     *     title: string,
     *     content: string,
     *     publishedAt: string|null,
     *     createdAt: string|null,
     *     updatedAt: string|null,
     *     author: string
     * }
     */
    public function transformOne(Article $article): array
    {
        return [
            'id' => $article->id,
            'slug' => $article->slug,
            'title' => $article->title,
            'content' => $article->content->toPlainText(),
            'publishedAt' => $article->published_at?->format('j. n. Y H:i:s'),
            'createdAt' => $article->created_at?->format('j. n. Y H:i:s'),
            'updatedAt' => $article->updated_at?->format('j. n. Y H:i:s'),
            'author' => $article->user->username,
        ];
    }
}
