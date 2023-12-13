<?php

namespace App\Http\Controllers\Admin;

use App\Commands\Runner\CreateRunner;
use App\Commands\Runner\CreateRunnerHandler;
use App\Commands\Runner\UpdateRunner;
use App\Commands\Runner\UpdateRunnerHandler;
use App\Http\Requests\StoreRunnerRequest;
use App\Http\Requests\UpdateRunnerRequest;
use App\Models\Runner;
use App\Services\PaginateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class RunnerController extends AdminController
{
    private const LIMIT = 50;

    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly CreateRunnerHandler $createRunnerHandler,
        private readonly UpdateRunnerHandler $updateRunnerHandler,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = $request->get('query');
        $runners = Runner::search($search)->paginate(self::LIMIT);
        $page = (int)$request->get('page', 1);

        return Inertia::render('Admin/Runners/Index', [
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

    public function edit(Runner $runner): Response
    {
        return Inertia::render('Admin/Runners/Edit', [
            'runner' => $runner,
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
            ));

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.runner_update_success'));

            return Redirect::route('admin.runners.edit', $runner->id);
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

            $runner->delete();

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.runner_delete_success'));

            return Redirect::route('admin.runners.index');
        } catch (\Exception $exception) {
            $this->withMessage(self::ALERT_ERROR, $exception->getMessage());

            return redirect()->back();
        }
    }
}
