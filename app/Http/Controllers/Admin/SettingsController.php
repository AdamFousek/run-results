<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function index(): RedirectResponse|Response
    {
        $user = $this->getUser();
        if (!$user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Admin/Settings/Index');
    }
}
