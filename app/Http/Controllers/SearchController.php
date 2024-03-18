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
        $search = $request->get('query');

        return Inertia::render('Search', [
            'search' => '',
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
