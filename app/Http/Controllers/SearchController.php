<?php

namespace App\Http\Controllers;

use App\Http\Transformers\Meilisearch\RaceListTransformer;
use App\Http\Transformers\Meilisearch\RunnerListTransformer;
use App\Queries\Race\RaceSearch;
use App\Queries\Race\RaceSearchHandler;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    public function __construct(
        private readonly RaceSearchHandler $raceSearchHandler,
        private readonly RunnerSearchQuery $runnerSearchQuery,
        private readonly RaceListTransformer $raceListTransformer,
        private readonly RunnerListTransformer $runnerListTransformer,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = (string)$request->get('search', '');

        $runners = null;
        $races = null;
        if ($search !== '') {
            $runners = $this->runnerSearchQuery->handle(new RunnerSearch(
                search: $search,
                page: 1,
                perPage: 50,
                sortBy: 'year',
                sortDirection: 'desc'
            ));

            $races = $this->raceSearchHandler->handle(new RaceSearch(
                search: $search,
                page: 1,
                perPage: 50,
                sortBy: 'date',
                sortDirection: 'desc'
            ));
        }

        return Inertia::render('Search', [
            'search' => $search,
            'runners' => $runners !== null ? $this->runnerListTransformer->transform($runners->items) : [],
            'races' => $races !== null ? $this->raceListTransformer->transform($races->items) : [],
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $search = trim($request->input('search', '') ?? '');

        $runners = $this->runnerSearchQuery->handle(new RunnerSearch(
            search: $search,
            page: 1,
            perPage: 10,
        ));

        $races = $this->raceSearchHandler->handle(new RaceSearch(
            search: $search,
            page: 1,
            perPage: 10,
        ));

        return response()->json([
            'runners' => $this->runnerListTransformer->transform($runners->items),
            'race' => $this->raceListTransformer->transform($races->items),
        ]);
    }
}
