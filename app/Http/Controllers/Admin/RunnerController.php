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
use App\Http\Transformers\Meilisearch\RunnerListTransformer;
use App\Models\Illuminate\Result;
use App\Models\Illuminate\Runner;
use App\Queries\Runner\RunnerSearch;
use App\Queries\Runner\RunnerSearchQuery;
use App\Services\RunnerSortService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RunnerController extends AdminController
{
    private const LIMIT = 50;

    public function __construct(
        private readonly CreateRunnerHandler $createRunnerHandler,
        private readonly UpdateRunnerHandler $updateRunnerHandler,
        private readonly RunnerSortService $sortService,
        private readonly RunnerSearchQuery $runnerSearchQuery,
        private readonly RunnerListTransformer $runnerListTransformer,
        private readonly MergerRunnerHandler $mergerRunnerHandler,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query', ''));
        $page = (int)$request->get('page', 1);
        $requestSort = $request->get('sort', RunnerSortService::DEFAULT_SORT);
        $sort = $this->sortService->resolveSort($requestSort);
        [$sortColumn, $sortDirection] = explode(':', $sort);

        $runners = $this->runnerSearchQuery->handle(new RunnerSearch(
            search: $search,
            page: $page,
            perPage: self::LIMIT,
            sortBy: $sortColumn,
            sortDirection: $sortDirection
        ));

        return Inertia::render('Admin/Runners/Index', [
            'runners' => $this->runnerListTransformer->transform($runners->items),
            'paginate' => [
                'page' => $page,
                'total' => $runners->estimatedTotal,
                'limit' => self::LIMIT,
                'onPage' => $runners->total,
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
            $targetRunner = Runner::findOrFail($data['runnerId'])->first();

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
