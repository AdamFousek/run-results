<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch\Results;

use App\Models\Meilisearch\Article;

class ArticleCollection
{
    /**
     * @param Article[] $items
     * @param int $total
     * @param int $estimatedTotal
     */
    public function __construct(
        public array $items,
        public int $total,
        public int $estimatedTotal,
    ) {
    }
}
