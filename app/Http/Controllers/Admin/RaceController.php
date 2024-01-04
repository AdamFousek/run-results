<?php

namespace App\Http\Controllers\Admin;

use App\Commands\Race\CreateRace;
use App\Commands\Race\CreateRaceCommand;
use App\Http\Requests\StoreRaceRequest;
use App\Http\Transformers\Race\RaceListTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Models\Race;
use App\Services\PaginateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RaceController extends AdminController
{
    private const LIMIT = 50;

    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly RaceListTransformer $transformer,
        private readonly RaceTransformer $raceTransformer,
        private readonly CreateRaceCommand $createRaceCommand,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $races = Race::search($search)->paginate(self::LIMIT);
        $page = (int)$request->get('page', 1);

        return Inertia::render('Admin/Races/Index', [
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

    public function create(): Response
    {
        $this->authorize('create', Race::class);

        return Inertia::render('Admin/Races/Create');
    }

    public function store(StoreRaceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $race = $this->createRaceCommand->handle(new CreateRace(
                name: $validated['name'],
                description: $validated['description'],
                date: $validated['date'],
                location: $validated['location'],
                distance: $validated['distance'],
                surface: $validated['surface'],
                type: $validated['type'],
                isParent: $validated['isParent'],
                parentId: $validated['parentId'],
            ));

            return Redirect::route('admin.race.edit', $race->id);
        } catch (\Throwable $th) {
            $this->withMessage(self::ALERT_ERROR, $th->getMessage());

            return back();
        }
    }

    public function edit(Race $race): Response
    {
        return Inertia::render('Admin/Races/Edit', [
            'race' => $this->raceTransformer->transform($race),
        ]);
    }
}
