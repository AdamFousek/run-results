<?php

namespace App\Http\Controllers\Admin;

use App\Models\Runner;
use Inertia\Inertia;
use Inertia\Response;

class RunnerController extends AdminController
{
    public function index(): Response
    {
        $runners = Runner::paginate(30);

        return Inertia::render('Admin/Runners/Index', [
            'runners' => $runners,
        ]);
    }
}
