<?php

namespace App\Http\Controllers;

use App\Http\Transformers\Race\RaceListTransformer;
use App\Http\Transformers\Race\RaceRunnerListTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Http\Transformers\Runner\TopRunnerTransformer;
use App\Models\Illuminate\Enums\RunnerGenderEnum;
use App\Models\Illuminate\Race;
use App\Models\Illuminate\UploadedFiles;
use App\Models\QueryResult\TopRunner;
use App\Queries\Race\RaceSearch;
use App\Queries\Race\RaceSearchHandler;
use App\Queries\Result\GetCategoriesByRaceIdHandler;
use App\Queries\Result\GetCategoriesByRaceIdQuery;
use App\Queries\Result\GetResultsHandler;
use App\Queries\Result\GetResultsQuery;
use App\Queries\Result\GetTopRunnersBy;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
use App\Queries\TopResult\GetTopResultsByQuery;
use App\Queries\TopResult\GetTopResultsQuery;
use App\Repositories\Illuminate\Results\TopRunnersResult;
use App\Services\PaginateService;
use App\Services\Providers\ResultStatsService;
use App\Services\RaceSortService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RaceController extends Controller
{
    private const int LIMIT = 30;

    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly RaceListTransformer $transformer,
        private readonly RaceRunnerListTransformer $raceRunnerListTransformer,
        private readonly RaceTransformer $raceTransformer,
        private readonly RaceSearchHandler $raceSearchHandler,
        private readonly \App\Http\Transformers\Meilisearch\RaceListTransformer $meilisearchRaceListTransformer,
        private readonly RaceSortService $sortService,
        private readonly ResultStatsService $resultStatsService,
        private readonly GetCategoriesByRaceIdHandler $getCategoriesByRaceIdHandler,
        private readonly GetResultsHandler $getResultsHandler,
        private readonly TopRunnerTransformer $topRunnerTransformer,
        private readonly RunnerSearchQuery $runnerSearchQuery,
        private readonly GetTopResultsByQuery $getTopResultsByQuery,
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
        $categories = $request->get('filterCategories', []);
        $filter = [
            'showFemale' => $showFemale === 'true',
            'showMale' => $showMale === 'true',
            'categories' => $categories,
        ];

        $files = $race->files->filter(fn(UploadedFiles $file) => (bool)$file->is_public)->map(fn(UploadedFiles $file) => [
            'name' => $file->name,
            'url' => $file->file_path,
        ]);

        $results = null;
        $childRaces = null;
        $paginate = null;
        $stats = null;
        if (!$race->is_parent) {
            $results = $this->getResultsHandler->handle(new GetResultsQuery(
                race: $race,
                search: $search,
                page: $page,
                showFemale: $filter['showFemale'],
                showMale: $filter['showMale'],
                categories: $filter['categories'],
            ));

            $paginate = [
                'links' => $this->paginateService->resolveLinks($results),
                'page' => $page,
                'total' => $results->total(),
                'limit' => self::LIMIT
            ];
        } else {
            $childRaces = $race->children()->orderBy('date', 'desc')->get();
            if ($race->tag !== null && $race->tag !== '') {
                $stats = $this->resultStatsService->provideStatsByRaceIds($race->tag);
            }
        }

        $total = $childRaces !== null ? $childRaces->count() : $results?->total();
        $metaDescription = $this->resolveMetaDescription($race, (int)$total);


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
            'categories' => $this->getCategoriesByRaceIdHandler->handle(new GetCategoriesByRaceIdQuery($race->id)),
        ];


        return Inertia::render('Races/Show', $data);
    }

    public function stats(Race $race): Response|RedirectResponse
    {
        if (!$race->is_parent) {
            if ($race->parent !== null) {
                return Redirect::route('races.stats', $race->parent->slug);
            }

            return Redirect::route('races.show', $race->slug);
        }

        $stats = null;
        $topMen = null;
        $topWomen = null;
        $topParticipant = null;
        if ($race->tag !== null && $race->tag !== '') {
            $stats = $this->resultStatsService->provideStatsByRaceIds($race->tag);
            $topMen = $this->getTopResultsByQuery->handle(new GetTopResultsQuery(
                raceTag: $race->tag,
                gender: RunnerGenderEnum::MALE,
                limit: 10,
            ));
            $topWomen = $this->getTopResultsByQuery->handle(new GetTopResultsQuery(
                raceTag: $race->tag,
                gender: RunnerGenderEnum::FEMALE,
                limit: 10,
            ));
        }

        $data = [
            'race' => $this->raceTransformer->transform($race),
            'head' => [
                'title' => $race->name . ' ' . trans('messages.races_stats_title'),
                'description' => trans('messages.race_stats_meta_description', [ 'race' => $race->name]),
            ],
            'stats' => $stats,
            'topWomen' => $topWomen !== null ? $this->topRunnerTransformer->transform($topWomen->items) : null,
            'topMen' => $topMen !== null ? $this->topRunnerTransformer->transform($topMen->items) : null,
            'topParticipant' => null,
        ];

        return Inertia::render('Races/RaceStats', $data);
    }

    public function topMen(Request $request, Race $race): Response|RedirectResponse
    {
        if (!$race->is_parent || $race->tag === null || $race->tag === '') {
            if ($race->parent !== null) {
                return Redirect::route('races.stats', $race->parent->slug);
            }

            return Redirect::route('races.show', $race->slug);
        }

        $search = trim($request->get('query'));
        $page = (int)$request->get('page', 1);
        $runners = $this->getTopResultsByQuery->handle(new GetTopResultsQuery(
            raceTag: $race->tag,
            gender: RunnerGenderEnum::MALE,
            limit: 100,
            offset: ($page - 1) * 100,
            search: $search,
        ));

        $data = [
            'head' => [
                'title' => $race->name . ' ' . trans('messages.topMen'),
                'description' => trans('messages.topMen_description', [ 'race' => $race->name]),
            ],
            'title' => trans('messages.topMen'),
            'race' => $this->raceTransformer->transform($race),
            'runners' => $this->topRunnerTransformer->transform($runners->items),
            'search' => $search,
            'paginate' => [
                'page' => $page,
                'total' => $runners->estimatedTotal,
                'limit' => self::LIMIT,
                'onPage' => $runners->total,
            ],
        ];

        return Inertia::render('Races/TopRunners', $data);
    }

    public function topWomen(Race $race): Response|RedirectResponse
    {
        if (!$race->is_parent) {
            if ($race->parent !== null) {
                return Redirect::route('races.stats', $race->parent->slug);
            }

            return Redirect::route('races.show', $race->slug);
        }

        $data = [
            'race' => $this->raceTransformer->transform($race),
        ];

        return Inertia::render('Races/TopRunners', $data);
    }

    public function topParticipant(Race $race): Response|RedirectResponse
    {
        if (!$race->is_parent) {
            if ($race->parent !== null) {
                return Redirect::route('races.stats', $race->parent->slug);
            }

            return Redirect::route('races.show', $race->slug);
        }

        $data = [
            'race' => $this->raceTransformer->transform($race),
        ];

        return Inertia::render('Races/TopRunners', $data);
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
            $raceName = $race->name . ' - ' . $race->date->format('j. n. Y');
        }

        $raceDesc = $race->description->toPlainText();
        $description = '';
        if ($raceDesc !== '') {
            $description = ' Podrobnosti o závodě: ' . $raceDesc;
        }

        return trans('messages.race_meta_description', [ 'race' => $raceName, 'count' => $total, 'description' => $description ]);
    }
}
