<?php

declare(strict_types=1);


namespace App\Commands\Article;

use App\Enum\TagType;
use App\Models\Illuminate\Article;
use App\Values\ArticleMeta;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;

class CreateArticleCommand
{
    public function handle(CreateArticle $command): Article
    {
        $article = new Article();
        $article->title = $command->title;
        $article->slug = $this->resolveSlug(Str::slug($command->title));
        $article->published_at = $command->publishedAt ?? now();
        $article->content = $command->content;
        $article->meta = new ArticleMeta($command->metaDescription);
        $article->user_id = $command->user->id;
        $article->save();

        $this->resolveTags($article, $command->tags);
        $this->resolveKeywords($article, $command->keywords);

        return $article;
    }

    private function resolveSlug(string $slug, int $try = 0): string
    {
        $newSlug = $slug;
        if ($try > 0) {
            $newSlug = $slug . '-' . $try;
        }

        if (Article::query()->whereSlug($newSlug)->exists()) {
            $try++;
            return $this->resolveSlug($slug, $try);
        }

        return $newSlug;
    }

    private function resolveTags(Article $article, array $tags): void
    {
        foreach ($tags as $tag) {
            $dbTag = Tag::findOrCreate($tag, TagType::ARTICLE_TAG->value);

            $article->attachTag($dbTag);
        }
    }

    private function resolveKeywords(Article $article, array $keywords): void
    {
        foreach ($keywords as $keyword) {
            $dbTag = Tag::findOrCreate($keyword, TagType::ARTICLE_KEYWORDS->value);

            $article->attachTag($dbTag);
        }
    }
}
