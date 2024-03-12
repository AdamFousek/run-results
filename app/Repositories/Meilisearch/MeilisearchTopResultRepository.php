<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch;

use App\Deserializer\TopResultDeserializer;
use App\Models\Illuminate\Result;
use App\Models\TopResult;
use App\Queries\TopResult\GetTopResultsQuery;
use App\Repositories\Meilisearch\Results\TopResultCollection;
use App\Repositories\TopResultRepositoryInterface;
use App\Serializer\TopResultSerializer;
use Illuminate\Support\Collection;
use Meilisearch\Client;

readonly class MeilisearchTopResultRepository implements TopResultRepositoryInterface
{
    public function __construct(
        private Client $client,
        private TopResultDeserializer $resultDeserializer,
        private TopResultSerializer $resultSerializer,
    ) {
    }

    #[\Override]
    public function find(GetTopResultsQuery $query): TopResultCollection
    {
        $index = $this->client->getIndex($this->getIndex());

        $filter = [];
        if ($query->gender !== null) {
            $filter[] = "runner.gender = '{$query->gender->value}'";
        }

        if ($query->raceTag !== '') {
            $filter[] = "race.tag = '{$query->raceTag}'";
        }

        $filter = [
            'filter' => $filter,
            'sort' => ['time:asc'],
            'limit' => $query->limit,
            'offset' => $query->offset,
        ];

        $search = null;
        if ($query->search !== '') {
            $search = $query->search;
        }

        $searchResult = $index->search($search, $filter);
        $items = collect();
        /** @var array{
         * id: int,
         * topPosition: int,
         * runner: array{
         * id: int,
         * firstName: string,
         * lastName: string,
         * year: int,
         * gender?: string
         * },
         * race: array{
         * id: int,
         * name: string,
         * slug: string,
         * tag: ?string,
         * date: ?int,
         * time: ?string
         * },
         * time: int
         * } $hit */
        foreach ($searchResult->getHits() as $hit) {
            $items->add($this->resultDeserializer->deserialize($hit));
        }

        return new TopResultCollection(
            items: $items,
            total: $searchResult->getHitsCount(),
            estimatedTotal: $searchResult->getEstimatedTotalHits(),
        );
    }

    #[\Override]
    public function upsert(Collection $results): void
    {
        $index = $this->client->getIndex($this->getIndex());

        $serialized = $results->map(fn (Result $result) => $this->resultSerializer->serialize($result));

        $index->updateDocuments($serialized->toArray());
    }

    public function deleteByTag(string $raceTag): void
    {
        $index = $this->client->getIndex($this->getIndex());

        $index->deleteDocuments(['filter' => "race.tag = '{$raceTag}'"]);
    }

    private function getIndex(): string
    {
        return (new TopResult())->searchableAs();
    }
}
