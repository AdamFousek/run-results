<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\Runner\RunnerListTransformer;
use App\Models\Illuminate\PairRunnerLog;
use App\Models\Illuminate\Runner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchRunnerController extends Controller
{
    public function __construct(
        private readonly RunnerListTransformer $runnerListTransformer
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $search = trim($request->input('search'));

        if ($search === '') {
            return response()->json([
                'runners' => [],
            ]);
        }

        $alreadyPairedRunners = PairRunnerLog::where('result', PairRunnerLog::RESULT_SUCCESS)->get()->pluck('runner_id')->all();
        $runners = Runner::search($search)->whereNotIn('id', $alreadyPairedRunners)->get();

        $transformedData = $this->runnerListTransformer->transform($runners);

        return response()->json([
            'runners' => $transformedData,
        ]);
    }
}
