<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch;

use App\Deserializer\ResultDeserializer;
use App\Models\Illuminate\Result;
use App\Queries\Result\GetRunnerResultsQuery;
use App\Repositories\Meilisearch\Results\ResultCollection;
use App\Repositories\ResultRepositoryInterface;
use App\Services\ResultSortService;
use Meilisearch\Client;

readonly class MeilisearchResultRepository implements ResultRepositoryInterface
{
    public function __construct(
        private Client $client,
        private ResultDeserializer $resultDeserializer,
    ) {
    }

    #[\Override]
    public function byIds(array $ids): ResultCollection
    {
        $index = $this->client->getIndex($this->getIndex());

        $filter = [
            'filter' => 'id IN [' . implode(',', $ids) . ']',
            'sort' => ['time:asc'],
            'limit' => 1000000,
        ];

        $search = $index->search(null, $filter);
        $items = collect();
        foreach ($search->getHits() as $hit) {
            $items->add($this->resultDeserializer->deserialize($hit));
        }

        return new ResultCollection(
            items: $items,
            total: $search->getHitsCount(),
            estimatedTotal: $search->getEstimatedTotalHits(),
        );
    }

    #[\Override]
    public function byQuery(GetRunnerResultsQuery $query): ResultCollection
    {
        $index = $this->client->getIndex($this->getIndex());

        $filter = [];
        if ($query->runner !== null) {
            $filter[] = "runner.id = {$query->runner->id}";
        }

        $sort = $query->sort;
        if ($sort === '') {
            $sort = ResultSortService::DEFAULT_SORT;
        }

        $filter = [
            'filter' => $filter,
            'sort' => [$sort],
            'limit' => $query->limit,
            'offset' => $query->offset
        ];

        $search = null;
        if ($query->search !== '') {
            $search = $query->search;
        }

        $search = $index->search($search, $filter);
        $items = collect();
        foreach ($search->getHits() as $hit) {
            $items->add($this->resultDeserializer->deserialize($hit));
        }

        return new ResultCollection(
            items: $items,
            total: $search->getHitsCount(),
            estimatedTotal: $search->getEstimatedTotalHits(),
        );
    }

    private function getIndex(): string
    {
        return (new Result())->searchableAs();
    }
}
