<?php

declare(strict_types=1);


namespace App\Commands\Article;

use App\Models\Illuminate\User;
use Carbon\Carbon;

readonly class CreateArticle
{
    /**
     * @param string $title
     * @param string $content
     * @param Carbon|null $publishedAt
     * @param string[] $tags
     * @param string[] $keywords
     * @param string $metaDescription
     * @param User $user
     */
    public function __construct(
        public string $title,
        public string $content,
        public ?Carbon $publishedAt,
        public array $tags,
        public array $keywords,
        public string $metaDescription,
        public User $user,
    ) {
    }
}
