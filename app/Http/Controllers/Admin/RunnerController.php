<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Runner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RunnerController extends AdminController
{
    public function index()
    {
        $runners = Runner::paginate(30);

        return Inertia::render('Admin/Runners/Index', [
            'runners' => $runners,
        ]);
    }
}
