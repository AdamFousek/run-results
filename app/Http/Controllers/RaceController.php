<?php

namespace App\Http\Controllers;

use App\Http\Transformers\Race\RaceListTransformer;
use App\Http\Transformers\Race\RaceRunnerListTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Http\Transformers\Runner\TopRunnerTransformer;
use App\Models\Illuminate\Race;
use App\Models\Illuminate\UploadedFiles;
use App\Queries\Race\RaceSearch;
use App\Queries\Race\RaceSearchHandler;
use App\Queries\Result\GetCategoriesByRaceIdHandler;
use App\Queries\Result\GetCategoriesByRaceIdQuery;
use App\Queries\Result\GetResultsHandler;
use App\Queries\Result\GetResultsQuery;
use App\Services\PaginateService;
use App\Services\Providers\ResultStatsService;
use App\Services\RaceSortService;
use Illuminate\Http\Request;
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
        $topWomen = null;
        $topMen = null;
        $topParticipant = null;
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
                $topWomen = $this->resultStatsService->provideTopWomenByRaceTag($race->tag);
                $topParticipant = $this->resultStatsService->provideTopParticipantByRaceTag($race->tag);
                $topMen = $this->resultStatsService->provideTopMenByRaceTag($race->tag);
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
            'topWomen' => $topWomen !== null ? $this->topRunnerTransformer->transform($topWomen->items) : null,
            'topMen' => $topMen !== null ? $this->topRunnerTransformer->transform($topMen->items) : null,
            'topParticipant' => $topParticipant !== null ? $this->topRunnerTransformer->transform($topParticipant->items) : null,
            'categories' => $this->getCategoriesByRaceIdHandler->handle(new GetCategoriesByRaceIdQuery($race->id)),
        ];


        return Inertia::render('Races/Show', $data);
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
