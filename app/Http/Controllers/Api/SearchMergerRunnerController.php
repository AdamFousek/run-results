<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\Meilisearch\RunnerListTransformer;
use App\Models\Illuminate\Runner;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchMergerRunnerController extends Controller
{
    public function __construct(
        private readonly RunnerSearchQuery $runnerSearch,
        private readonly RunnerListTransformer $runnerListTransformer
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Runner $runner, Request $request): JsonResponse
    {
        $search = trim($request->input('search', '') ?? '');

        if ($search === '') {
            return response()->json([
                'runners' => [],
            ]);
        }

        $runners = $this->runnerSearch->handle(new RunnerSearch(
            search: $search,
            page: 1,
            perPage: 100,
            withoutIds: [$runner->id],
        ));

        $transformedData = $this->runnerListTransformer->transform($runners->items);

        return response()->json([
            'runners' => $transformedData,
        ]);
    }
}
