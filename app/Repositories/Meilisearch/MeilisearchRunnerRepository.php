<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch;

use App\Deserializer\RunnerDeserializer;
use App\Models\Illuminate\Runner;
use App\Queries\Runner\RunnerSearch;
use App\Repositories\Meilisearch\Results\RunnerCollection;
use App\Repositories\RunnerRepository;
use Meilisearch\Client;

class MeilisearchRunnerRepository implements RunnerRepository
{
    public function __construct(
        private readonly Client $client,
        private readonly RunnerDeserializer $runnerDeserializer,
    ) {
    }

    public function search(RunnerSearch $query): RunnerCollection
    {
        $index = $this->client->getIndex($this->getIndex());

        $filter = [];
        $filter['limit'] = $query->perPage;
        $filter['offset'] = ($query->page - 1) * $query->perPage;

        if ($query->sortBy !== '') {
            $filter['sort'] = [$query->sortBy . ':' . $query->sortDirection];
        }

        $search = $index->search($query->search, $filter);

        $items = collect();
        /** @var array{
         * id: int,
         * userId: int,
         * firstName: string,
         * lastName: string,
         * year: int,
         * city: string|null,
         * club: string|null,
         * resultsCount: int,
         * createdAt: string,
         * updatedAt: string,
         * upsertedAt: string
         * } $hit
         */
        foreach ($search->getHits() as $hit) {
            $items->add($this->runnerDeserializer->deserialize($hit));
        }

        return new RunnerCollection(
            items: $items,
            total: $search->getHitsCount(),
            estimatedTotal: $search->getEstimatedTotalHits(),
        );
    }

    private function getIndex(): string
    {
        return (new Runner())->searchableAs();
    }
}
