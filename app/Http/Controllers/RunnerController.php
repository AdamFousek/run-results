<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRunnerRequest;
use App\Http\Requests\UpdateRunnerRequest;
use App\Models\Runner;
use App\Services\PaginateService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RunnerController extends Controller
{
    private const LIMIT = 30;

    public function __construct(
        private readonly PaginateService $paginateService,
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

    public function show(Runner $runner): Response
    {
        $races = [];

        return Inertia::render('Runners/Show', [
            'runner' => $runner,
            'races' => $races,
        ]);
    }
}
