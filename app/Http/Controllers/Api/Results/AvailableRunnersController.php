<?php

namespace App\Http\Controllers\Api\Results;

use App\Http\Controllers\Controller;
use App\Http\Transformers\Runner\RunnerListTransformer;
use App\Models\Race;
use App\Models\Result;
use App\Models\Runner;
use Illuminate\Http\Request;

class AvailableRunnersController extends Controller
{
    public function __construct(
        private readonly RunnerListTransformer $runnerListTransformer,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', Result::class);

        $raceId = (int)$request->input('raceId');

        $race = Race::whereId($raceId)->firstOrFail();

        $resultRunners = $race->results->pluck('runner_id');

        $runners = Runner::query()->whereNotIn('id', $resultRunners)->get();

        $data = [];
        foreach ($runners as $runner) {
            $data[] = [
                'value' => $runner->id,
                'label' => implode(' ', [$runner->last_name, $runner->first_name, $runner->year]),
            ];
        }

        return response()->json([
            'runners' => $data,
        ]);
    }
}
