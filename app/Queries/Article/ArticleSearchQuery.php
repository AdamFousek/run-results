<?php

declare(strict_types=1);


namespace App\Queries\Article;

use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\Meilisearch\Results\ArticleCollection;

readonly class ArticleSearchQuery
{
    public function __construct(
        private ArticleRepositoryInterface $repository,
    ) {
    }

    public function handle(ArticleSearch $query): ArticleCollection
    {
        return $this->repository->search($query);
    }
}
