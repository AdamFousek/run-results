<?php

declare(strict_types=1);


namespace App\Repositories\Meilisearch;

use App\Deserializer\ResultDeserializer;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Illuminate\Result;
use App\Queries\Result\GetResultsQuery;
use App\Repositories\Meilisearch\Results\ResultCollection;
use App\Repositories\ResultRepositoryInterface;
use App\Services\MeilisearchSort\ResultSortService;
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
        /**
         * @var array{
         *      id: int,
         *      runner: array{
         *       id: int,
         *       firstName: string,
         *       lastName: string,
         *       year: int,
         *       gender?: string
         *       },
         *      race: array{
         *       id: int,
         *       name: string,
         *       slug: string,
         *       tag: string|null,
         *       date: int|null,
         *       time: string|null,
         *       vintage: int|null,
         *       distance: int|null,
         *       location: string|null,
         *       surface: string|null,
         *       type: string|null,
         *       region: string|null,
         *       },
         *      startingNumber: int,
         *      position: int,
         *      time: ?string,
         *      category: ?string,
         *      categoryPosition: ?int,
         *      club: ?string,
         *      dnf: bool,
         *      dns: bool
         *  } $hit
         */
        foreach ($search->getHits() as $hit) {
            $items->add($this->resultDeserializer->deserialize($hit));
        }

        return new ResultCollection(
            items: $items,
            total: $search->getHitsCount(),
            estimatedTotal: $search->getEstimatedTotalHits(),
        );
    }

    /**
     * @throws \JsonException
     */
    #[\Override]
    public function byQuery(GetResultsQuery $query): ResultCollection
    {
        $index = $this->client->getIndex($this->getIndex());

        $filter = [];
        if ($query->runner !== null) {
            $filter[] = "runner.id = {$query->runner->id}";
        }

        if ($query->race !== null) {
            $filter[] = "race.id = {$query->race->id}";
        }

        if ($query->showFemale === false) {
            $female = RunnerGenderEnum::FEMALE->value;
            $filter[] = "runner.gender != '{$female}'";
        }

        if ($query->showMale === false) {
            $male = RunnerGenderEnum::MALE->value;
            $filter[] = "runner.gender != '{$male}'";
        }

        if ($query->categories !== []) {
            $categories = json_encode($query->categories, JSON_THROW_ON_ERROR);
            $filter[] = "category IN {$categories}";
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
        /**
         * @var array{
         *      id: int,
         *      runner: array{
         *       id: int,
         *       firstName: string,
         *       lastName: string,
         *       year: int,
         *       gender?: string
         *       },
         *      race: array{
         *       id: int,
         *       name: string,
         *       slug: string,
         *       tag: string|null,
         *       date: int|null,
         *       time: string|null,
         *       vintage: int|null,
         *       distance: int|null,
         *       location: string|null,
         *       surface: string|null,
         *       type: string|null,
         *       region: string|null,
         *       },
         *      startingNumber: int,
         *      position: int,
         *      time: ?string,
         *      category: ?string,
         *      categoryPosition: ?int,
         *      club: ?string,
         *      dnf: bool,
         *      dns: bool,
         *  } $hit
         */
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
    public function delete(int $id): void
    {
        $index = $this->client->getIndex($this->getIndex());

        $index->deleteDocument($id);
    }

    private function getIndex(): string
    {
        return (new Result())->searchableAs();
    }
}
