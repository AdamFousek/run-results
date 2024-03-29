<?php

namespace App\Http\Controllers;

use App\Http\Transformers\Race\RaceListTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Models\Illuminate\Race;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function __construct(
        private readonly RaceListTransformer $raceTransformer,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $races = Race::query()->orderBy('created_at', 'desc')->limit(5)->get();
        $races->loadCount('results');

        return Inertia::render('Welcome', [
            'races' => $this->raceTransformer->transform($races),
        ]);
    }
}
