<?php

namespace App\Http\Controllers\Admin;

use App\Commands\Race\CreateRace;
use App\Commands\Race\CreateRaceCommand;
use App\Commands\Race\UpdateRace;
use App\Commands\Race\UpdateRaceCommand;
use App\Http\Requests\StoreRaceRequest;
use App\Http\Requests\UpdateRaceRequest;
use App\Http\Transformers\Race\RaceListTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Models\Race;
use App\Services\PaginateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
        private readonly UpdateRaceCommand $updateRaceCommand,
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

        $optionsType = DB::table('races')->select(DB::raw('distinct type'))->get();
        $optionsTag = DB::table('races')->select(DB::raw('distinct tag'))->get();
        $optionsSurface = DB::table('races')->select(DB::raw('distinct surface'))->get();

        $parentRaces = Race::whereIsParent(true)->get();

        return Inertia::render('Admin/Races/Create', [
            'parentRaces' => $this->transformer->transform($parentRaces),
            'optionsType' => $this->transformOptions($optionsType, 'type'),
            'optionsTag' => $this->transformOptions($optionsTag, 'tag'),
            'optionsSurface' => $this->transformOptions($optionsSurface, 'surface'),
        ]);
    }

    public function store(StoreRaceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $race = $this->createRaceCommand->handle(new CreateRace(
                name: $validated['name'],
                description: $validated['description'] ?? '',
                date: $validated['date'] ?? '',
                time: $validated['time'] ?? '',
                location: $validated['location'] ?? '',
                distance: $validated['distance'] ?? 0,
                surface: $validated['surface'] ?? '',
                type: $validated['type'] ?? '',
                tag: $validated['tag'] ?? '',
                vintage: $validated['vintage'] ?? null,
                region: $validated['region'] ?? '',
                latitude: $validated['latitude'] ?? null,
                longitude: $validated['longitude'] ?? null,
                isParent: $validated['isParent'] ?? false,
                parentId: $validated['parentId'] ?? null,
            ));

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.race_created_success'));

            return Redirect::route('admin.races.edit', $race->id);
        } catch (\Throwable $th) {
            $this->withMessage(self::ALERT_ERROR, $th->getMessage());

            return back();
        }
    }

    public function edit(Race $race): Response
    {
        $optionsType = DB::table('races')->select(DB::raw('distinct type'))->get();
        $optionsTag = DB::table('races')->select(DB::raw('distinct tag'))->get();
        $optionsSurface = DB::table('races')->select(DB::raw('distinct surface'))->get();

        $parentRaces = Race::whereIsParent(true)->get();

        return Inertia::render('Admin/Races/Edit', [
            'parentRaces' => $this->transformer->transform($parentRaces),
            'race' => $this->raceTransformer->transform($race),
            'optionsType' => $this->transformOptions($optionsType, 'type'),
            'optionsTag' => $this->transformOptions($optionsTag, 'tag'),
            'optionsSurface' => $this->transformOptions($optionsSurface, 'surface'),
        ]);
    }

    public function update(Race $race, UpdateRaceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $race = $this->updateRaceCommand->handle(new UpdateRace(
                race: $race,
                name: $validated['name'],
                description: $validated['description'] ?? '',
                date: $validated['date'] ?? '',
                time: $validated['time'] ?? '',
                location: $validated['location'] ?? '',
                distance: $validated['distance'] ?? '',
                surface: $validated['surface'] ?? '',
                type: $validated['type'] ?? '',
                tag: $validated['tag'] ?? '',
                vintage: $validated['vintage'] ?? null,
                region: $validated['region'] ?? '',
                latitude: $validated['latitude'] ?? null,
                longitude: $validated['longitude'] ?? null,
                isParent: $validated['isParent'],
                parentId: $validated['parentId'],
            ));

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.race_update_success'));

            return Redirect::route('admin.races.edit', $race->id);
        } catch (\Throwable $th) {
            $this->withMessage(self::ALERT_ERROR, $th->getMessage());

            return back();
        }
    }

    public function destroy(Race $race, Request $request): RedirectResponse
    {
        try {
            $this->authorize('delete', $race);

            $request->validate([
                'raceId' => 'required|exists:races,id',
            ]);

            $race->results()->delete();
            $race->delete();

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.race_delete_success'));

            return Redirect::route('admin.races.index');
        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return redirect()->back();
        }
    }

    /**
     * @param Collection $optionsType
     * @param string $column
     * @return array<array<string, string>>
     */
    private function transformOptions(Collection $optionsType, string $column): array
    {
        $result = [];
        foreach ($optionsType as $item) {
            $result[] = [
                'value' => $item->$column,
                'label' => $item->$column,
            ];
        }

        return $result;
    }
}
