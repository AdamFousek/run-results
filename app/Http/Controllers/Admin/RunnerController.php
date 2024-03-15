<?php

namespace App\Http\Controllers\Admin;

use App\Commands\Runner\CreateRunner;
use App\Commands\Runner\CreateRunnerHandler;
use App\Commands\Runner\MergerRunner;
use App\Commands\Runner\MergerRunnerHandler;
use App\Commands\Runner\UpdateRunner;
use App\Commands\Runner\UpdateRunnerHandler;
use App\Http\Requests\MergeRunnerRequest;
use App\Http\Requests\StoreRunnerRequest;
use App\Http\Requests\UpdateRunnerRequest;
use App\Http\Transformers\Runner\RunnerTransformer;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
use App\Services\IlluminateSort\IlluminateRunnerSortService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RunnerController extends AdminController
{
    private const int LIMIT = 50;

    public function __construct(
        private readonly CreateRunnerHandler $createRunnerHandler,
        private readonly UpdateRunnerHandler $updateRunnerHandler,
        private readonly IlluminateRunnerSortService $sortService,
        private readonly RunnerSearchQuery $runnerSearchQuery,
        private readonly RunnerTransformer $runnerTransformer,
        private readonly MergerRunnerHandler $mergerRunnerHandler,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query', ''));
        $page = (int)$request->get('page', 1);
        $requestSort = $request->get('sort', IlluminateRunnerSortService::DEFAULT_SORT);
        $sort = $this->sortService->resolveSort($requestSort);
        [$sortColumn, $sortDirection] = explode(':', $sort);

        if ($search !== '') {
            $searchRunners = $this->runnerSearchQuery->handle(new RunnerSearch(
                search: $search,
                page: 1,
                perPage: 100000,
            ));

            $runnerIds = $searchRunners->items->map(fn (\App\Models\Meilisearch\Runner $runner) => $runner->getId())->toArray();

            $runners = Runner::query()
                ->withCount('results')
                ->when($runnerIds !== [], function($query) use ($runnerIds) {
                    return $query->whereIn('id', $runnerIds);
                })
                ->when($runnerIds === [], function($query) use ($search) {
                    return $query->where(DB::raw("concat(first_name, ' ', last_name)"), 'LIKE', "%".$search."%");
                })
                ->orderBy($sortColumn, $sortDirection)
                ->paginate(self::LIMIT);
        } else {
            $runners = Runner::query()
                ->withCount('results')
                ->orderBy($sortColumn, $sortDirection)
                ->paginate(self::LIMIT);
        }


        return Inertia::render('Admin/Runners/Index', [
            'runners' => array_map(fn (Runner $runner) => $this->runnerTransformer->transform($runner), $runners->items()),
            'paginate' => [
                'page' => $page,
                'total' => $runners->total(),
                'limit' => self::LIMIT,
                'onPage' => $runners->perPage(),
            ],
            'search' => $search,
            'activeSort' => $sort,
        ]);
    }

    public function edit(Runner $runner): Response
    {
        return Inertia::render('Admin/Runners/Edit', [
            'runner' => $runner,
            'resultCount' => $runner->results()->count(),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Runner::class);

        return Inertia::render('Admin/Runners/Create');
    }

    public function store(StoreRunnerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $runner = $this->createRunnerHandler->handle(
                new CreateRunner(
                    $data['first_name'],
                    $data['last_name'],
                    $data['day'] ?? null,
                    $data['month'] ?? null,
                    $data['year'],
                    $data['city'],
                    $data['club'],
                    $data['gender'] ?? '',
                )
            );

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.runner_create_success'));

            return Redirect::route('admin.runners.edit', $runner->id);
        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return redirect()->back();
        }
    }

    public function update(Runner $runner, UpdateRunnerRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $runner = $this->updateRunnerHandler->handle(new UpdateRunner(
                $runner,
                $data['first_name'],
                $data['last_name'],
                $data['day'] ?? null ,
                $data['month'] ?? null,
                $data['year'],
                $data['city'],
                $data['club'],
                $data['gender'] ?? '',
            ));

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.runner_update_success'));

            return Redirect::route('admin.runners.edit', $runner->id);
        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return redirect()->back();
        }
    }

    public function merge(MergeRunnerRequest $request, Runner $runner): RedirectResponse
    {
        $data = $request->validated();

        try {
            /** @var Runner $targetRunner */
            $targetRunner = Runner::whereId($data['runnerId'])->first();

            $targetRunner = $this->mergerRunnerHandler->handle(new MergerRunner(
                source: $runner,
                target: $targetRunner,
            ));

            $targetRunner->searchable();

            $this->withMessage(self::ALERT_SUCCESS, 'messages.runners_merged_successfully');

            return Redirect::route('admin.runners.edit', $targetRunner->id);
        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return redirect()->back();
        }
    }

    public function destroy(Request $request, Runner $runner): RedirectResponse
    {
        try {
            $this->authorize('delete', $runner);

            $request->validate([
                'runnerId' => 'required|exists:runners,id',
            ]);

            Result::whereRunnerId($runner->id)->delete();

            $runner->delete();

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.runner_delete_success'));

            return Redirect::route('admin.runners.index');
        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return redirect()->back();
        }
    }
}
