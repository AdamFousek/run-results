<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRaceRequest;
use App\Http\Requests\UpdateRaceRequest;
use App\Http\Transformers\RaceListTransformer;
use App\Models\Race;
use App\Services\PaginateService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RaceController extends Controller
{
    private const LIMIT = 20;

    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly RaceListTransformer $transformer,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = $request->get('query');
        $races = Race::search($search)->paginate(self::LIMIT);
        $page = (int)$request->get('page', 1);

        return Inertia::render('Races/Index', [
            'races' => $this->transformer->transform($races->items()),
            'paginate' => [
                'links' => $this->paginateService->resolveLinks($races),
                'page' => $page,
                'total' => $races->total(),
                'limit' => self::LIMIT
            ],
            'search' => $search,
        ]);
    }

    public function show(Race $race): Response
    {
        $runners = [];

        return Inertia::render('Runner/Show', [
            'race' => $race,
            'runners' => $runners,
        ]);
    }
}
