<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\Runner\RunnerListTransformer;
use App\Queries\Runner\GetRunnerDuplicityByLastNameQuery;
use App\Services\Providers\DuplicityRunnersService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DuplicityController extends Controller
{
    public function __construct(
        private readonly DuplicityRunnersService $duplicityRunnersService,
        private readonly RunnerListTransformer $runnerListTransformer,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $runners = $this->duplicityRunnersService->provide();

        return Inertia::render('Admin/Runners/Duplicity', [
            'runners' => $this->runnerListTransformer->transform($runners),
        ]);
    }
}
