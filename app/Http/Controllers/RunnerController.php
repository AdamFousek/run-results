<?php

namespace App\Http\Controllers;

use App\Http\Providers\ChartRunnerDataProvider;
use App\Http\Transformers\Meilisearch\RunnerListTransformer;
use App\Http\Transformers\Runner\RunnerRaceListTransformer;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;
use App\Queries\Race\GetRaceIdsBySearch;
use App\Queries\Race\GetRaceIdsBySearchHandler;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
use App\Services\PaginateService;
use App\Services\RaceSortService;
use App\Services\RunnerSortService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RunnerController extends Controller
{
    private const LIMIT = 30;

    public function __construct(
        private readonly RunnerRaceListTransformer $runnerRaceListTransformer,
        private readonly ChartRunnerDataProvider $chartRunnerDataProvider,
        private readonly RunnerSearchQuery $runnerSearchQuery,
        private readonly RunnerListTransformer $runnerListTransformer,
        private readonly GetRaceIdsBySearchHandler $getRaceIdsBySearchHandler,
        private readonly PaginateService $paginateService,
        private readonly RunnerSortService $sortService,
        private readonly RaceSortService $raceSortService,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query', ''));
        $page = (int)$request->get('page', 1);
        $requestSort = $request->get('sort', RunnerSortService::DEFAULT_SORT);
        $sort = $this->sortService->resolveSort($requestSort);
        [$sortColumn, $sortDirection] = explode(':', $sort);

        $runners = $this->runnerSearchQuery->handle(new RunnerSearch(
            search: $search,
            page: $page,
            perPage: self::LIMIT,
            sortBy: $sortColumn,
            sortDirection: $sortDirection
        ));

        return Inertia::render('Runners/Index', [
            'runners' => $this->runnerListTransformer->transform($runners->items),
            'paginate' => [
                'page' => $page,
                'total' => $runners->estimatedTotal,
                'limit' => self::LIMIT,
                'onPage' => $runners->total,
            ],
            'search' => $search,
            'activeSort' => $sort,
        ]);
    }

    public function show(Request $request, Runner $runner): Response
    {
        $page = (int)$request->get('page', 1);
        $search = trim($request->get('query'));
        $requestSort = $request->get('sort', RunnerSortService::DEFAULT_SORT);
        $sort = $this->raceSortService->resolveSort($requestSort);
        [$sortColumn, $sortDirection] = explode(':', $sort);
        $orderBy = 'races.' . $sortColumn;

        if ($search !== '') {
            $raceIds = $this->getRaceIdsBySearchHandler->handle(new GetRaceIdsBySearch($search));
            $results = Result::query()->selectRaw('results.*')->whereRunnerId($runner->id)
                ->join('races', 'results.race_id', '=', 'races.id')
                ->orderBy($orderBy, $sortDirection)
                ->whereIn('races.id', $raceIds)
                ->with('race')
                ->paginate(self::LIMIT);
        } else {
            $results = Result::query()->selectRaw('results.*')->whereRunnerId($runner->id)
                ->join('races', 'results.race_id', '=', 'races.id')
                ->orderBy($orderBy, $sortDirection)
                ->with('race')
                ->paginate(self::LIMIT);
        }

        $chartData = $this->chartRunnerDataProvider->provide($runner);

        return Inertia::render('Runners/Show', [
            'runner' => $runner,
            'results' => $this->runnerRaceListTransformer->transform($results->items()),
            'search' => $search,
            'paginate' => [
                'links' => $this->paginateService->resolveLinks($results),
                'page' => $page,
                'total' => $results->total(),
                'limit' => self::LIMIT
            ],
            'chartData' => $chartData,
        ]);
    }
}
