<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRunnerRequest;
use App\Http\Requests\UpdateRunnerRequest;
use App\Models\Runner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RunnerController extends Controller
{
    private const LIMIT = 30;

    public function index(Request $request): Response
    {
        $search = $request->get('query');
        $runners = Runner::search($search)->paginate(self::LIMIT);
        $page = (int)$request->get('page', 1);

        return Inertia::render('Runner/Index', [
            'runners' => $runners->items(),
            'paginate' => [
                'links' => $this->resolveLinks($runners),
                'page' => $page,
                'total' => $runners->total(),
                'limit' => self::LIMIT
            ],
            'search' => $search,
        ]);
    }

    public function show(Runner $runner): Response
    {
        $races = [];

        return Inertia::render('Runner/Show', [
            'runner' => $runner,
            'races' => $races,
        ]);
    }

    /**
     * @param LengthAwarePaginator $runners
     * @return array<array<string, string|bool>>
     */
    private function resolveLinks(LengthAwarePaginator $runners): array
    {
        $result = [];

        if ($runners->lastPage() === 1) {
            return $result;
        }

        foreach($runners->getUrlRange(1, $runners->lastPage()) as $page => $link) {
            $result[] = [
                'link' => $link,
                'label' => $page,
                'active' => $page === $runners->currentPage(),
            ];
        }

        return $result;
    }
}
