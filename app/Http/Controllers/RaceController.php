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
use App\Services\RaceSortService;
use App\Services\ResultStatsService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RaceController extends Controller
{
    private const LIMIT = 30;

    private const LIMIT_RUNNERS = 50;

    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly RaceListTransformer $transformer,
        private readonly RaceRunnerListTransformer $raceRunnerListTransformer,
        private readonly RaceTransformer $raceTransformer,
        private readonly RunnerSearchQuery $runnerSearchQuery,
        private readonly RaceSearchHandler $raceSearchHandler,
        private readonly \App\Http\Transformers\Meilisearch\RaceListTransformer $meilisearchRaceListTransformer,
        private readonly RaceSortService $sortService,
        private readonly ResultStatsService $resultStatsService,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $page = (int)$request->get('page', 1);
        $requestSort = $request->get('sort', RaceSortService::DEFAULT_SORT);
        $sort = $this->sortService->resolveSort($requestSort);
        [$sortColumn, $sortDirection] = explode(':', $sort);

        $races = $this->raceSearchHandler->handle(new RaceSearch(
            search: $search,
            page: $page,
            perPage: self::LIMIT,
            wihtoutParent: $search === '',
            sortBy: $sortColumn,
            sortDirection: $sortDirection,
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
            'activeSort' => $sort,
        ]);
    }

    public function show(Request $request, Race $race): Response
    {
        $search = trim($request->get('query'));
        $runnerId = (int)$request->get('runnerId');
        $page = (int)$request->get('page', 1);
        $showFemale = trim($request->get('showFemale', 'true'));
        $showMale = trim($request->get('showMale', 'true'));
        $filter = [
            'showFemale' => $showFemale === 'true',
            'showMale' => $showMale === 'true',
        ];

        $files = $race->files->filter(fn(UploadedFiles $file) => $file->is_public)->map(fn(UploadedFiles $file) => [
            'name' => $file->name,
            'url' => $file->file_path,
        ]);

        $results = null;
        $childRaces = null;
        $paginate = null;
        $stats = null;
        if (!$race->is_parent) {
            $results = $this->resolveResults($race, $search, $page, $filter);
            $paginate = [
                'links' => $this->paginateService->resolveLinks($results),
                'page' => $page,
                'total' => $results->total(),
                'limit' => self::LIMIT
            ];
        } else {
            $childRaces = $race->children()->orderBy('date', 'desc')->get();
            if ($race->tag !== null || $race->tag !== '') {
                $stats = $this->resultStatsService->provideStatsByRaceIds($race->tag);
            }
        }

        $total = $childRaces !== null ? $childRaces->count() : $results->total();
        $metaDescription = $this->resolveMetaDescription($race, $total);

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
            'filter' => $filter,
            'stats' => $stats,
        ];


        return Inertia::render('Races/Show', $data);
    }

    /**
     * @param Race $race
     * @param string $search
     * @param int $page
     * @param array<string, bool> $filter
     * @return LengthAwarePaginator
     */
    private function resolveResults(Race $race, string $search, int $page, array $filter): LengthAwarePaginator
    {
        if ($search !== '') {
            if (is_numeric($search)) {
                return Result::whereRaceId($race->id)->whereStartingNumber((int)$search)->orderBy('position')->paginate(self::LIMIT_RUNNERS);
            }

            $runners = $this->runnerSearchQuery->handle(new RunnerSearch($search, $page, self::LIMIT_RUNNERS));
            $runnerIds = $runners->items->map(fn(Runner $runner) => $runner->getId())->toArray();
            return Result::whereRaceId($race->id)->orderBy('position')->with('runner')->whereIn('runner_id', $runnerIds)->paginate(self::LIMIT_RUNNERS);
        }

        $query = Result::whereRaceId($race->id)->with('runner');

        if (!$filter['showFemale']) {
            $query->withoutFemale();
        }

        if (!$filter['showMale']) {
            $query->withoutMale();
        }

        return $query->orderBy('position')->paginate(self::LIMIT_RUNNERS);
    }

    private function resolveMetaDescription(Race $race, int $total): string
    {
        if ($race->is_parent) {
            $raceName = $race->name;

            return trans('messages.race_parent_meta_description', [ 'race' => $raceName, 'count' => $total ]);
        }

        if ($race->date === null) {
            $raceName = $race->name;
        } else {
            $raceName = $race->name . ' ' . $race->date->format('j. n. Y') . $race->description->toPlainText();
        }

        return trans('messages.race_meta_description', [ 'race' => $raceName, 'count' => $total ]);
    }
}
