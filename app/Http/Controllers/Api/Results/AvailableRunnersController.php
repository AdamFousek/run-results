<?php

namespace App\Http\Controllers\Api\Results;

use App\Http\Controllers\Controller;
use App\Models\Illuminate\Race;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvailableRunnersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
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
