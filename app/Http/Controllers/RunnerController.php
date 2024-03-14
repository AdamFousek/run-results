<?php

namespace App\Http\Controllers;

use App\Http\Providers\ChartRunnerDataProvider;
use App\Http\Transformers\Meilisearch\ResultListTransformer;
use App\Http\Transformers\Meilisearch\RunnerListTransformer;
use App\Models\Illuminate\Runner;
use App\Queries\Result\GetResultsHandler;
use App\Queries\Result\GetResultsQuery;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
use App\Services\ResultSortService;
use App\Services\RunnerSortService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RunnerController extends Controller
{
    private const int LIMIT = 30;

    public function __construct(
        private readonly ChartRunnerDataProvider $chartRunnerDataProvider,
        private readonly RunnerSearchQuery $runnerSearchQuery,
        private readonly RunnerListTransformer $runnerListTransformer,
        private readonly RunnerSortService $sortService,
        private readonly GetResultsHandler $getRunnerResultsHandler,
        private readonly ResultListTransformer $resultListTransformer,
        private readonly ResultSortService $resultSortService,
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
        $requestSort = $request->get('sort', ResultSortService::DEFAULT_SORT);
        $sort = $this->resultSortService->resolveSort($requestSort);

        $results = $this->getRunnerResultsHandler->handle(new GetResultsQuery(
            runner: $runner,
            search: $search,
            limit: self::LIMIT,
            offset: ($page - 1) * self::LIMIT,
            sort: $sort,
        ));

        $chartData = $this->chartRunnerDataProvider->provide($runner);

        return Inertia::render('Runners/Show', [
            'runner' => $runner,
            'results' => $this->resultListTransformer->transform($results->items),
            'search' => $search,
            'sort' => $requestSort,
            'paginate' => [
                'page' => $page,
                'total' => $results->estimatedTotal,
                'limit' => self::LIMIT,
                'onPage' => $results->total,
            ],
            'head' => [
                'title' => $runner->full_name,
                'description' => trans('messages.runner_metadescription', [ 'runner' => $runner->full_name, 'count' => $results->total ]),
                'canonical' => route('runners.show', $runner),
            ],
            'chartData' => $chartData,
        ]);
    }
}
