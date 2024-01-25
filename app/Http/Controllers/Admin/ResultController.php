<?php

namespace App\Http\Controllers\Admin;

use App\Commands\Results\CreateResult;
use App\Commands\Results\CreateResultCommand;
use App\Commands\Results\UpdateResult;
use App\Commands\Results\UpdateResultHandler;
use App\Commands\UploadFileResult\CreateUploadFileResult;
use App\Commands\UploadFileResult\CreateUploadFileResultHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Http\Requests\UploadResultRequest;
use App\Http\Transformers\Race\RaceResultsTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Http\Transformers\Result\ResultTransformer;
use App\Jobs\ProcessResults;
use App\Models\Race;
use App\Models\Result;
use App\Services\PaginateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ResultController extends Controller
{
    const LIMIT = 30;

    public function __construct(
        private readonly RaceResultsTransformer $transformer,
        private readonly RaceTransformer $raceTransformer,
        private readonly PaginateService $paginateService,
        private readonly ResultTransformer $resultTransformer,
        private readonly CreateResultCommand $createResultCommand,
        private readonly UpdateResultHandler $updateResultHandler,
        private readonly CreateUploadFileResultHandler $createUploadFileResultHandler,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        if ($search !== '') {
            $races = Race::search($search)->paginate(self::LIMIT);
        } else {
            $races = Race::has('results')->paginate(self::LIMIT);
        }
        $page = (int)$request->get('page', 1);

        return Inertia::render('Admin/Results/Index', [
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                time: $validated['time'] ?? '',
                categoryPosition: $validated['categoryPosition'] ?? 0,
                category: $validated['category'] ?? '',
                dnf: $validated['DNF'] ?? false,
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

        return Inertia::render('Admin/Results/Show', [
            'race' => $this->raceTransformer->transform($race),
            'results' => $this->resultTransformer->transform($results)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        //
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
                time: $validated['time'] ?? '',
                categoryPosition: $validated['categoryPosition'] ?? 0,
                category: $validated['category'] ?? '',
                dnf: $validated['DNF'] ?? false,
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
        try {
            $data = $request->validated();

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
        try {
            $this->authorize('delete', $result);

            $result->delete();

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.result_delete_success'));

            return back();
        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return redirect()->back();
        }
    }
}