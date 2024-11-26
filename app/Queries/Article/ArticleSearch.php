<?php

declare(strict_types=1);


namespace App\Queries\Article;

use Carbon\Carbon;

class ArticleSearch
{
    public function __construct(
        public string $search,
        public int $page,
        public int $perPage,
        public ?Carbon $publishedAt = null,
        public string $sortBy = '',
        public string $sortDirection = '',
    ) {
    }
}
