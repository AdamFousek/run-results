<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRaceRequest;
use App\Http\Requests\UpdateRaceRequest;
use App\Http\Transformers\RaceListTransformer;
use App\Http\Transformers\RaceRunnerListTransformer;
use App\Http\Transformers\RaceTransformer;
use App\Models\Race;
use App\Models\Runner;
use App\Services\PaginateService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RaceController extends Controller
{
    private const LIMIT = 20;

    public const SORT_NAME_ASC = 'name:asc';
    public const SORT_NAME_DESC = 'name:desc';
    public const SORT_DATE_ASC = 'date:asc';
    public const SORT_DATE_DESC = 'date:desc';
    public const SORT_LOCATION_ASC = 'location:asc';
    public const SORT_LOCATION_DESC = 'location:desc';
    public const SORT_DISTANCE_ASC = 'distance:asc';
    public const SORT_DISTANCE_DESC = 'distance:desc';

    public const SORTS = [
        self::SORT_NAME_ASC => 'sort.name_asc',
        self::SORT_NAME_DESC => 'sort.name_desc',
        self::SORT_DATE_ASC => 'sort.date_asc',
        self::SORT_DATE_DESC => 'sort.date_desc',
        self::SORT_LOCATION_ASC => 'sort.location_asc',
        self::SORT_LOCATION_DESC => 'sort.location_desc',
        self::SORT_DISTANCE_ASC => 'sort.distance_asc',
        self::SORT_DISTANCE_DESC => 'sort.distance_desc',
    ];

    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly RaceListTransformer $transformer,
        private readonly RaceRunnerListTransformer $raceRunnerListTransformer,
        private readonly RaceTransformer $raceTransformer,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $sort = $this->resolveSort($request);
        [$sortColumn, $sortDirection] = explode(':', $sort);
        $races = Race::search($search)->orderBy($sortColumn, $sortDirection)->paginate(self::LIMIT);
        $page = (int)$request->get('page', 1);

        return Inertia::render('Races/Index', [
            'races' => $this->transformer->transform($races->items()),
            'paginate' => [
                'links' => $this->paginateService->resolveLinks($races),
                'page' => $page,
                'total' => $races->total(),
                'limit' => self::LIMIT
            ],
            'search' => $search,
            'sortOptions' => $this->buildSort(),
            'activeSort' => $this->resolveSort($request),
        ]);
    }

    public function show(Request $request, Race $race): Response
    {
        $search = trim($request->get('query'));
        $isNumeric = is_numeric($search);
        if ($search !== '') {
            if ($isNumeric) {
                $results = $race->results()->where('starting_number', (int)$search)->orderBy('time')->with('runner')->get();
            } else {
                $runnerIds = Runner::search($search)->get()->pluck('id');
                $results = $race->results()->orderBy('time')->with('runner')->whereIn('runner_id', $runnerIds)->get();
            }
        } else {
            $results = $race->results()->orderBy('time')->with('runner')->get();
        }

        return Inertia::render('Races/Show', [
            'race' => $this->raceTransformer->transform($race),
            'results' => $this->raceRunnerListTransformer->transform($results),
        ]);
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function buildSort(): array
    {
        $sorts = [];
        foreach (self::SORTS as $key => $value) {
            $sorts[] = [
                'label' => trans($value),
                'value' => $key,
            ];
        }

        return $sorts;
    }

    private function resolveSort(Request $request): string
    {
        $sort = $request->get('sort', self::SORT_NAME_ASC);

        if (!array_key_exists($sort, self::SORTS)) {
            $sort = self::SORT_NAME_ASC;
        }

        return $sort;
    }
}
