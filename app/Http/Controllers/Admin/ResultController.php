<?php

namespace App\Http\Controllers\Admin;

use App\Commands\Results\CreateResult;
use App\Commands\Results\CreateResultCommand;
use App\Commands\Results\RemoveResultCommand;
use App\Commands\Results\UpdateResult;
use App\Commands\Results\UpdateResultHandler;
use App\Commands\UploadFileResult\CreateUploadFileResult;
use App\Commands\UploadFileResult\CreateUploadFileResultHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Http\Requests\UploadResultRequest;
use App\Http\Transformers\Race\RaceListTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Http\Transformers\Result\ResultTransformer;
use App\Http\Transformers\Result\ResultUploadsTransformer;
use App\Jobs\ProcessResults;
use App\Models\Illuminate\Race;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\UploadFileResult;
use App\Queries\Race\RaceSearch;
use App\Queries\Race\RaceSearchHandler;
use App\Services\IlluminateSort\IlluminateRaceSortService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ResultController extends Controller
{
    private const int LIMIT = 30;

    public function __construct(
        private readonly RaceTransformer $raceTransformer,
        private readonly ResultTransformer $resultTransformer,
        private readonly CreateResultCommand $createResultCommand,
        private readonly UpdateResultHandler $updateResultHandler,
        private readonly CreateUploadFileResultHandler $createUploadFileResultHandler,
        private readonly ResultUploadsTransformer $resultUploadsTransformer,
        private readonly RaceSearchHandler $raceSearchHandler,
        private readonly IlluminateRaceSortService $raceSortService,
        private readonly RaceListTransformer $transformer,
        private readonly RemoveResultCommand $removeResultCommand,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $page = (int)$request->get('page', 1);
        $requestSort = $request->get('sort', IlluminateRaceSortService::DEFAULT_SORT);
        $sort = $this->raceSortService->resolveSort($requestSort);
        [$sortColumn, $sortDirection] = explode(':', $sort);

        if ($search !== '') {
            $searchRaces = $this->raceSearchHandler->handle(new RaceSearch(
                search: $search,
                page: 1,
                perPage: 100000,
                wihtoutParent: false,
            ));

            $raceIds = $searchRaces->items->map(fn (\App\Models\Meilisearch\Race $race) => $race->getId())->toArray();

            $races = Race::query()
                ->withCount('results')
                ->when($raceIds !== [], function($query) use ($raceIds) {
                    return $query->whereIn('id', $raceIds);
                })
                ->when($raceIds === [], function($query) use ($search) {
                    return $query->where(DB::raw("concat(name, ' ', location, ' ', vintage)"), 'LIKE', "%".$search."%");
                })
                ->orderBy($sortColumn, $sortDirection)
                ->paginate(self::LIMIT);
        } else {
            $races = Race::query()
                ->withCount('results')
                ->orderBy($sortColumn, $sortDirection)
                ->paginate(self::LIMIT);
        }

        return Inertia::render('Admin/Results/Index', [
            'races' => $this->transformer->transform($races->items()),
            'paginate' => [
                'page' => $page,
                'total' => $races->total(),
                'limit' => self::LIMIT,
                'onPage' => $races->perPage(),
            ],
            'search' => $search,
            'activeSort' => $sort,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResultRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $result = $this->createResultCommand->handle(new CreateResult(
                raceId: $validated['raceId'],
                runnerId: $validated['runnerId'],
                position: $validated['position'] ?? 0,
                startingNumber: $validated['startingNumber'] ?? 0,
                time: $validated['time'] ?? null,
                categoryPosition: $validated['categoryPosition'] ?? 0,
                category: $validated['category'] ?? '',
                club: $validated['club'] ?? '',
                dnf: $validated['DNF'] ?? false,
                dns: $validated['DNS'] ?? false,
            ));

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.result_create_success'));

            return back();
        } catch (\Throwable $th) {
            $this->withMessage(self::ALERT_ERROR, $th->getMessage());

            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Race $race): Response
    {
        $results = $race->results()->with('runner')->orderBy('position')->get();

        $uploads = UploadFileResult::whereRaceId($race->id)->orderBy('processed_at', 'desc')->with('rows')->get();

        return Inertia::render('Admin/Results/Show', [
            'race' => $this->raceTransformer->transform($race),
            'results' => $this->resultTransformer->transform($results),
            'uploads' => $this->resultUploadsTransformer->transform($uploads),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResultRequest $request, Result $result): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $result = $this->updateResultHandler->handle(new UpdateResult(
                result: $result,
                raceId: $validated['raceId'],
                runnerId: $validated['runnerId'],
                position: $validated['position'] ?? 0,
                startingNumber: $validated['startingNumber'] ?? 0,
                time: $validated['time'] ?? null,
                categoryPosition: $validated['categoryPosition'] ?? 0,
                category: $validated['category'] ?? '',
                club: $validated['club'] ?? '',
                dnf: $validated['DNF'] ?? false,
                dns: $validated['DNS'] ?? false,
            ));

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.result_create_success'));

            return back();
        } catch (\Throwable $th) {
            $this->withMessage(self::ALERT_ERROR, $th->getMessage());

            return back();
        }
    }

    public function upload(UploadResultRequest $request, Race $race): RedirectResponse
    {
        $data = $request->validated();

        try {
            $file = $data['results'];
            if (!$file instanceof UploadedFile) {
                throw new \Exception(trans('result_file_could_not_be_uploaded'));
            }

            $path = '/results/' . $race->id . '/' . Str::slug('results ' . time()) . '.' . $file->getClientOriginalExtension();
            $file->storeAs($path);

            $uploadFileResult = $this->createUploadFileResultHandler->handle(new CreateUploadFileResult(
                race: $race,
                file: $path
            ));

            $resultsIds = $race->results->pluck('id')->toArray();
            $this->removeResultCommand->handle($resultsIds);
            $race->results()->delete();

            ProcessResults::dispatch($uploadFileResult);

            return back();
        } catch (\Throwable $th) {
            $this->withMessage(self::ALERT_ERROR, $th->getMessage());

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result): RedirectResponse
    {
        $this->authorize('delete', $result);
        try {
            $result->delete();

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.result_delete_success'));

            return back();
        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return redirect()->back();
        }
    }

    public function destroyAll(Race $race): RedirectResponse
    {
        $this->authorize('deleteAll', Result::class);
        try {
            $results = Result::whereRaceId($race->id)->delete();

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.result_delete_success'));

            return back();
        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return redirect()->back();
        }
    }
}
