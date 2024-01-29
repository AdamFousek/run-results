<?php

namespace App\Http\Controllers;

use App\Http\Transformers\Race\RaceListTransformer;
use App\Http\Transformers\Race\RaceRunnerListTransformer;
use App\Http\Transformers\Race\RaceTransformer;
use App\Models\Race;
use App\Models\Result;
use App\Models\Runner;
use App\Services\PaginateService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RaceController extends Controller
{
    private const LIMIT = 20;

    private const LIMIT_RUNNERS = 50;

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
        if ($search !== '') {
            $races = Race::search($search)
                ->where('is_parent', 0)
                ->orderBy($sortColumn, $sortDirection)
                ->paginate(self::LIMIT);
        } else {
            $races = Race::query()
                ->where('is_parent', 0)
                ->orderBy($sortColumn, $sortDirection)
                ->paginate(self::LIMIT);
        }
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
        $page = (int)$request->get('page', 1);

        $results = null;
        $childRaces = null;
        $paginate = null;
        if (!$race->is_parent) {
            $results = $this->resolveResults($race, $search);
            $paginate = [
                'links' => $this->paginateService->resolveLinks($results),
                'page' => $page,
                'total' => $results->total(),
                'limit' => self::LIMIT
            ];
        } else {
            $childRaces = $race->children()->orderBy('date', 'desc')->get();
        }

        $metaDescription = $this->resolveMetaDescription($race);

        $data = [
            'race' => $this->raceTransformer->transform($race),
            'childRaces' => $childRaces !== null ? $this->transformer->transform($childRaces) : [],
            'results' => $results !== null ? $this->raceRunnerListTransformer->transform($results->items()) : [],
            'paginate' => $paginate,
            'head' => [
                'title' => $race->name,
                'description' => $metaDescription,
            ]
        ];


        return Inertia::render('Races/Show', $data);
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

    private function resolveResults(Race $race, string $search): LengthAwarePaginator
    {
        if ($search !== '') {
            if (is_numeric($search)) {
                return Result::whereRaceId($race->id)->whereStartingNumber((int)$search)->orderBy('position')->with('runner')->paginate(self::LIMIT_RUNNERS);
            }

            $runnerIds = Runner::search($search)->get()->pluck('id');
            return Result::whereRaceId($race->id)->orderBy('position')->with('runner')->whereIn('runner_id', $runnerIds)->paginate(self::LIMIT_RUNNERS);
        }

        return Result::whereRaceId($race->id)->orderBy('position')->with('runner')->paginate(self::LIMIT_RUNNERS);
    }

    private function resolveMetaDescription(Race $race): string
    {
        if ($race->is_parent) {
            return $race->name;
        }

        if ($race->date === null) {
            return $race->name;
        }

        return $race->name . ' ' . $race->date->format('j. n. Y');
    }
}
