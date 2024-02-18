<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch;

use App\Deserializer\RaceDeserializer;
use App\Models\Illuminate\Race;
use App\Queries\Race\GetRaceIdsBySearch;
use App\Queries\Race\RaceSearch;
use App\Repositories\Meilisearch\Results\RaceCollection;
use App\Repositories\RaceRepository;
use Meilisearch\Client;

class MeilisearchRaceRepository implements RaceRepository
{
    public function __construct(
        private readonly Client $client,
        private readonly RaceDeserializer $raceDeserializer,
    ) {
    }

    public function search(RaceSearch $query): RaceCollection
    {
        $index = $this->client->getIndex($this->getIndex());

        $filter = [];
        $filter['limit'] = $query->perPage;
        $filter['offset'] = ($query->page - 1) * $query->perPage;
        if ($query->wihtoutParent) {
            $filter['filter'] = ['isParent = false'];
        }

        if ($query->filterBy !== '') {
            $filter['sort'] = [$query->filterBy . ':' . $query->filterDirection];
        }

        $search = $index->search($query->search, $filter);

        $items = collect();
        /** @var array{
         * id: int,
         * name: string,
         * slug: string,
         * description: string,
         * date: int|null,
         * time: string|null,
         * location: string,
         * region: string,
         * distance: int,
         * vintage: int,
         * surface: string,
         * type: string,
         * tag: string,
         * isParent: bool,
         * parent: array{id: int, name: string, slug: string}|null,
         * _geo: array{lat: float, lng: float},
         * files: array{
         * id: int,
         * name: string,
         * url: string,
         * isPublic: bool
         * }[],
         * runnerCount: int,
         * createdAt: int|null,
         * updatedAt: int|null,
         * upsertedAt: int
         * } $hit
         */
        foreach ($search->getHits() as $hit) {
            $items->add($this->raceDeserializer->deserialize($hit));
        }

        return new RaceCollection(
            items: $items,
            total: $search->getHitsCount(),
            estimatedTotal: $search->getEstimatedTotalHits(),
        );
    }

    /**
     * @param GetRaceIdsBySearch $search
     * @return array
     */
    public function getIds(GetRaceIdsBySearch $search): array
    {
        $index = $this->client->getIndex($this->getIndex());

        $filter = [];
        $filter['limit'] =  100000;
        $filter['offset'] = 0;

        $result = $index->search($search->search, $filter);

        return collect($result->getHits())->pluck('id')->toArray();
    }

    private function getIndex(): string
    {
        return (new Race())->searchableAs();
    }
}
