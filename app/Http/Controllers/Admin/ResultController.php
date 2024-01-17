<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\Race\RaceResultsTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Http\Transformers\Result\ResultTransformer;
use App\Models\Race;
use App\Models\Result;
use App\Services\PaginateService;
use Illuminate\Http\Request;
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
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        if ($search !== '') {
            $races = Race::search($search)->paginate(self::LIMIT);
        } else{
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        //
    }
}
