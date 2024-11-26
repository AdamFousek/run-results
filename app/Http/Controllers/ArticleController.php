<?php

namespace App\Http\Controllers;

use App\Models\Illuminate\Article;
use App\Queries\Article\ArticleSearch;
use App\Queries\Article\ArticleSearchQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    private const int LIMIT = 10;

    public function __construct(
        private readonly ArticleSearchQuery $articleSearchQuery,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $page = (int)$request->get('page', 1);

        $articles = $this->articleSearchQuery->handle(new ArticleSearch(
            search: $search,
            page: $page,
            perPage: 10,
            publishedAt:  new Carbon(),
            sortBy: 'publishedAt',
            sortDirection: 'desc',
        ));

        return Inertia::render('Article/Index', [
            'articles' => Article::all(),
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Article $article): Response
    {
        return Inertia::render('Article/Show', [
            'article' => $article,
        ]);
    }
}
