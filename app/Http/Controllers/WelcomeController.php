<?php

namespace App\Http\Controllers;

use App\Http\Transformers\Meilisearch\ArticleListTransformer;
use App\Http\Transformers\Race\RaceListTransformer;
use App\Models\Illuminate\Race;
use App\Queries\Article\ArticleSearch;
use App\Queries\Article\ArticleSearchQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function __construct(
        private readonly RaceListTransformer $raceTransformer,
        private readonly ArticleSearchQuery $artlicleSearchQuery,
        private readonly ArticleListTransformer $articleListTransformer,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $races = Race::query()->orderBy('created_at', 'desc')->limit(5)->get();
        $races->loadCount('results');

        $articles = $this->artlicleSearchQuery->handle(new ArticleSearch(
            search: '',
            page: 1,
            perPage: 5,
            publishedAt:  new Carbon(),
            sortBy: 'publishedAt',
            sortDirection: 'desc',
        ));

        return Inertia::render('Welcome/Index', [
            'races' => $this->raceTransformer->transform($races),
            'articles' => $this->articleListTransformer->transform($articles->items),
        ]);
    }
}
