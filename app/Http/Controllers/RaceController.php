<?php

namespace App\Http\Controllers;

use App\Http\Transformers\Race\RaceListTransformer;
use App\Http\Transformers\Race\RaceRunnerListTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Models\Illuminate\Race;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\UploadedFiles;
use App\Models\Meilisearch\Runner;
use App\Queries\Race\RaceSearch;
use App\Queries\Race\RaceSearchHandler;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
use App\Services\PaginateService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RaceController extends Controller
{
    private const LIMIT = 30;

    private const LIMIT_RUNNERS = 50;

    public const SORT_NAME_ASC = 'name:asc';
    public const SORT_NAME_DESC = 'name:desc';
    public const SORT_DATE_ASC = 'date:asc';
    public const SORT_DATE_DESC = 'date:desc';
    public const SORT_LOCATION_ASC = 'location:asc';
    public const SORT_LOCATION_DESC = 'location:desc';
    public const SORT_DISTANCE_ASC = 'distance:asc';
    public const SORT_DISTANCE_DESC = 'distance:desc';

    public const SORTS = [
        self::SORT_NAME_ASC => 'sort.name_asc',
        self::SORT_NAME_DESC => 'sort.name_desc',
        self::SORT_DATE_ASC => 'sort.date_asc',
        self::SORT_DATE_DESC => 'sort.date_desc',
        self::SORT_LOCATION_ASC => 'sort.location_asc',
        self::SORT_LOCATION_DESC => 'sort.location_desc',
        self::SORT_DISTANCE_ASC => 'sort.distance_asc',
        self::SORT_DISTANCE_DESC => 'sort.distance_desc',
    ];

    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly RaceListTransformer $transformer,
        private readonly RaceRunnerListTransformer $raceRunnerListTransformer,
        private readonly RaceTransformer $raceTransformer,
        private readonly RunnerSearchQuery $runnerSearchQuery,
        private readonly RaceSearchHandler $raceSearchHandler,
        private readonly \App\Http\Transformers\Meilisearch\RaceListTransformer $meilisearchRaceListTransformer,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $page = (int)$request->get('page', 1);
        $sort = $this->resolveSort($request);
        [$sortColumn, $sortDirection] = explode(':', $sort);

        $races = $this->raceSearchHandler->handle(new RaceSearch(
            search: $search,
            page: $page,
            perPage: self::LIMIT,
            wihtoutParent: $search === '',
        ));

        return Inertia::render('Races/Index', [
            'races' => $this->meilisearchRaceListTransformer->transform($races->items),
            'paginate' => [
                'page' => $page,
                'total' => $races->estimatedTotal,
                'limit' => self::LIMIT,
                'onPage' => $races->total,
            ],
            'search' => $search,
            'sortOptions' => $this->buildSort(),
            'activeSort' => $this->resolveSort($request),
        ]);
    }

    public function show(Request $request, Race $race): Response
    {
        $search = trim($request->get('query'));
        $runnerId = (int)$request->get('runnerId');
        $page = (int)$request->get('page', 1);

        $files = $race->files->filter(fn(UploadedFiles $file) => $file->is_public)->map(fn(UploadedFiles $file) => [
            'name' => $file->name,
            'url' => $file->file_path,
        ]);

        $results = null;
        $childRaces = null;
        $paginate = null;
        if (!$race->is_parent) {
            $results = $this->resolveResults($race, $search, $page);
            $paginate = [
                'links' => $this->paginateService->resolveLinks($results),
                'page' => $page,
                'total' => $results->total(),
                'limit' => self::LIMIT
            ];
        } else {
            $childRaces = $race->children()->orderBy('date', 'desc')->get();
        }

        $metaDescription = $this->resolveMetaDescription($race);

        $data = [
            'race' => $this->raceTransformer->transform($race),
            'childRaces' => $childRaces !== null ? $this->transformer->transform($childRaces) : [],
            'results' => $results !== null ? $this->raceRunnerListTransformer->transform($results->items()) : [],
            'paginate' => $paginate,
            'head' => [
                'title' => $race->name,
                'description' => $metaDescription,
            ],
            'selectedRunner' => $runnerId === 0 ? null : $runnerId,
            'files' => $files,
        ];


        return Inertia::render('Races/Show', $data);
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function buildSort(): array
    {
        $sorts = [];
        foreach (self::SORTS as $key => $value) {
            $sorts[] = [
                'label' => trans($value),
                'value' => $key,
            ];
        }

        return $sorts;
    }

    private function resolveSort(Request $request): string
    {
        $sort = $request->get('sort', self::SORT_DATE_DESC);

        if (!array_key_exists($sort, self::SORTS)) {
            $sort = self::SORT_DATE_DESC;
        }

        return $sort;
    }

    private function resolveResults(Race $race, string $search, int $page): LengthAwarePaginator
    {
        if ($search !== '') {
            if (is_numeric($search)) {
                return Result::whereRaceId($race->id)->whereStartingNumber((int)$search)->orderBy('position')->with('runner')->paginate(self::LIMIT_RUNNERS);
            }

            $runners = $this->runnerSearchQuery->handle(new RunnerSearch($search, $page, self::LIMIT_RUNNERS));
            $runnerIds = $runners->items->map(fn(Runner $runner) => $runner->getId())->toArray();
            return Result::whereRaceId($race->id)->orderBy('position')->with('runner')->whereIn('runner_id', $runnerIds)->paginate(self::LIMIT_RUNNERS);
        }

        return Result::whereRaceId($race->id)->orderBy('position')->with('runner')->paginate(self::LIMIT_RUNNERS);
    }

    private function resolveMetaDescription(Race $race): string
    {
        if ($race->is_parent) {
            return $race->name;
        }

        if ($race->date === null) {
            return $race->name;
        }

        return $race->name . ' ' . $race->date->format('j. n. Y') . $race->description->toPlainText();
    }
}
