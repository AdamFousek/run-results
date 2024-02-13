<?php

namespace App\Http\Controllers;

use App\Http\Providers\ChartRunnerDataProvider;
use App\Http\Transformers\Runner\RunnerRaceListTransformer;
use App\Models\Illuminate\Race;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;
use App\Services\PaginateService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RunnerController extends Controller
{
    private const LIMIT = 50;

    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly RunnerRaceListTransformer $runnerRaceListTransformer,
        private readonly ChartRunnerDataProvider $chartRunnerDataProvider,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = $request->get('query');
        $runners = Runner::search($search)->paginate(self::LIMIT);
        $page = (int)$request->get('page', 1);

        return Inertia::render('Runners/Index', [
            'runners' => $runners->items(),
            'paginate' => [
                'links' => $this->paginateService->resolveLinks($runners),
                'page' => $page,
                'total' => $runners->total(),
                'limit' => self::LIMIT
            ],
            'search' => $search,
        ]);
    }

    public function show(Request $request, Runner $runner): Response
    {
        $search = trim($request->get('query'));
        if ($search !== '') {
            $raceIds = Race::search($search)->get()->pluck('id');
            $results = Result::query()->selectRaw('results.*')->whereRunnerId($runner->id)
                ->join('races', 'results.race_id', '=', 'races.id')
                ->orderBy('races.date', 'desc')
                ->whereIn('races.id', $raceIds)
                ->with('race')
                ->get();
        } else {
            $results = Result::query()->selectRaw('results.*')->whereRunnerId($runner->id)
                ->join('races', 'results.race_id', '=', 'races.id')
                ->orderBy('races.date', 'desc')
                ->with('race')
                ->get();
        }

        $chartData = $this->chartRunnerDataProvider->provide($runner);

        return Inertia::render('Runners/Show', [
            'runner' => $runner,
            'results' => $this->runnerRaceListTransformer->transform($results),
            'search' => $search,
            'chartData' => $chartData,
        ]);
    }
}
