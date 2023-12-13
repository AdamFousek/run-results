<?php

namespace App\Http\Controllers\Admin;

use App\Http\Transformers\Race\RaceListTransformer;
use App\Models\Race;
use App\Services\PaginateService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RaceController extends AdminController
{
    private const LIMIT = 50;

    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly RaceListTransformer $transformer,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $races = Race::search($search)->paginate(self::LIMIT);
        $page = (int)$request->get('page', 1);

        return Inertia::render('Admin/Races/Index', [
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
}
