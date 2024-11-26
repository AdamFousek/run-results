<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch;

use App\Deserializer\ArticleDeserializer;
use App\Models\Illuminate\Article;
use App\Queries\Article\ArticleSearch;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\Meilisearch\Results\ArticleCollection;
use Meilisearch\Client;

class MeilisearchArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        private Client $client,
        private ArticleDeserializer $articleDeserializer,
    ) {
    }

    public function search(ArticleSearch $query): ArticleCollection
    {
        $index = $this->client->getIndex($this->getIndex());

        $filter = [];
        $filter['limit'] = $query->perPage;
        $filter['offset'] = ($query->page - 1) * $query->perPage;

        if ($query->publishedAt !== null) {
            $filter['filter'] = [
                'publishedAt <= ' . $query->publishedAt->timestamp,
            ];
        }

        if ($query->sortBy !== '') {
            $filter['sort'] = [$query->sortBy . ':' . $query->sortDirection];
        }

        $search = $index->search($query->search, $filter);

        $items = [];
        /** @var array{
         * id: int,
         * title: string,
         * content: string,
         * author: string,
         * publishedAt: ?int,
         * tags: string[],
         * metaDescription: string,
         * metaKeywords: string[]
         * } $hit
         */
        foreach ($search->getHits() as $hit) {
            $items[] = $this->articleDeserializer->deserialize($hit);
        }

        return new ArticleCollection(
            items: $items,
            total: $search->getHitsCount(),
            estimatedTotal: $search->getEstimatedTotalHits(),
        );
    }

    public function delete(int $id): void
    {
        $index = $this->client->getIndex($this->getIndex());

        $index->deleteDocument($id);
    }

    private function getIndex(): string
    {
        return (new Article())->searchableAs();
    }
}
