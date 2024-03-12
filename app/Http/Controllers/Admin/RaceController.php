<?php

namespace App\Http\Controllers\Admin;

use App\Commands\Race\CreateRace;
use App\Commands\Race\CreateRaceCommand;
use App\Commands\Race\UpdateRace;
use App\Commands\Race\UpdateRaceCommand;
use App\Commands\UploadedFile\CreateUploadedFile;
use App\Commands\UploadedFile\CreateUploadedFileHandler;
use App\Http\Requests\StoreRaceRequest;
use App\Http\Requests\UpdateRaceRequest;
use App\Http\Requests\UploadFileRequest;
use App\Http\Transformers\Race\RaceListTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Models\Illuminate\Race;
use App\Queries\Race\RaceSearch;
use App\Queries\Race\RaceSearchHandler;
use App\Services\PaginateService;
use App\Services\RaceSortService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class RaceController extends AdminController
{
    private const LIMIT = 50;

    public function __construct(
        private readonly RaceListTransformer $transformer,
        private readonly RaceTransformer $raceTransformer,
        private readonly CreateRaceCommand $createRaceCommand,
        private readonly UpdateRaceCommand $updateRaceCommand,
        private readonly CreateUploadedFileHandler $createUploadedFileHandler,
        private readonly RaceSortService $sortService,
        private readonly RaceSearchHandler $raceSearchHandler,
        private readonly \App\Http\Transformers\Meilisearch\RaceListTransformer $meilisearchRaceListTransformer,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $page = (int)$request->get('page', 1);
        $requestSort = $request->get('sort', RaceSortService::DEFAULT_SORT);
        $sort = $this->sortService->resolveSort($requestSort);
        [$sortColumn, $sortDirection] = explode(':', $sort);

        $races = $this->raceSearchHandler->handle(new RaceSearch(
            search: $search,
            page: $page,
            perPage: self::LIMIT,
            wihtoutParent: $search === '',
            sortBy: $sortColumn,
            sortDirection: $sortDirection,
        ));

        return Inertia::render('Admin/Races/Index', [
            'races' => $this->meilisearchRaceListTransformer->transform($races->items),
            'paginate' => [
                'page' => $page,
                'total' => $races->estimatedTotal,
                'limit' => self::LIMIT,
                'onPage' => $races->total,
            ],
            'search' => $search,
            'activeSort' => $sort,
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
        $files = $race->files;
        $results = $race->results;

        $parentRaces = Race::whereIsParent(true)->get();

        return Inertia::render('Admin/Races/Edit', [
            'parentRaces' => $this->transformer->transform($parentRaces),
            'race' => $this->raceTransformer->transform($race),
            'optionsType' => $this->transformOptions($optionsType, 'type'),
            'optionsTag' => $this->transformOptions($optionsTag, 'tag'),
            'optionsSurface' => $this->transformOptions($optionsSurface, 'surface'),
            'files' => $files,
            'resultCount' => $results->count(),
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

    public function uploadFile(Race $race, UploadFileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $file = $data['file'];
            $name = $data['name'] ?? '';
            $isPublic = $data['isPublic'] ?? false;
            if (!$file instanceof UploadedFile) {
                throw new \Exception(trans('result_file_could_not_be_uploaded'));
            }

            $path = '/files/' . $race->id . '/' . Str::slug($name) . '.' . $file->getClientOriginalExtension();
            $file->storePubliclyAs($path);

            $uploadedFile = $this->createUploadedFileHandler->handle(new CreateUploadedFile(
                path: $path,
                name: $name,
                isPublic: $isPublic,
                filableId: $race->id,
                filableType: get_class($race),
            ));

            return back();
        } catch (\Throwable $th) {
            $this->withMessage(self::ALERT_ERROR, $th->getMessage());

            return back();
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
