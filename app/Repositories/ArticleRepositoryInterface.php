<?php

declare(strict_types=1);


namespace App\Repositories;


use App\Queries\Article\ArticleSearch;
use App\Repositories\Meilisearch\Results\ArticleCollection;

interface ArticleRepositoryInterface
{
    public function search(ArticleSearch $query): ArticleCollection;

    public function delete(int $id): void;
}
