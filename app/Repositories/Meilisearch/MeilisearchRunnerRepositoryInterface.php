<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch;

use App\Deserializer\RunnerDeserializer;
use App\Models\Illuminate\Runner;
use App\Queries\Runner\RunnerSearch;
use App\Repositories\Meilisearch\Results\RunnerCollection;
use App\Repositories\RunnerRepositoryInterface;
use Meilisearch\Client;

readonly class MeilisearchRunnerRepositoryInterface implements RunnerRepositoryInterface
{
    public function __construct(
        private Client $client,
        private RunnerDeserializer $runnerDeserializer,
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

        if ($query->withoutIds !== []) {
            $filter['filter'] = ['id NOT IN [' . implode(',', $query->withoutIds) . ']'];
        }

        $search = $index->search($query->search, $filter);

        $items = collect();
        /** @var array{
         * id: int,
         * userId: int|null,
         * firstName: string,
         * lastName: string,
         * year: int,
         * city: string|null,
         * club: string|null,
         * gender: string|null,
         * resultsCount: int,
         * createdAt: ?int,
         * updatedAt: ?int,
         * upsertedAt: int
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

    public function searchByNameAndYear(string $lastName, string $firstName, int $year): RunnerCollection
    {
        $index = $this->client->getIndex($this->getIndex());

        $filter = [];
        $filter['limit'] = 100000;
        $filter['filter'] = [
            'lastName = ' . $lastName,
            'year = ' . $year,
        ];

        $search = $index->search($lastName . ' ' . $firstName, $filter);

        $items = collect();
        /** @var array{
         * id: int,
         * userId: int|null,
         * firstName: string,
         * lastName: string,
         * year: int,
         * city: string|null,
         * club: string|null,
         * gender: string|null,
         * resultsCount: int,
         * createdAt: ?int,
         * updatedAt: ?int,
         * upsertedAt: int
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

    #[\Override]
    public function delete(int $id): void
    {
        $index = $this->client->getIndex($this->getIndex());

        $index->deleteDocument($id);
    }
}
