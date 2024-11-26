<?php

namespace App\Http\Controllers\Admin;

use App\Commands\Article\CreateArticle;
use App\Commands\Article\CreateArticleCommand;
use App\Enum\TagType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Transformers\Article\ArticleListTransformer;
use App\Http\Transformers\Tag\TagTransformer;
use App\Models\Illuminate\Article;
use App\Models\Illuminate\Runner;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Tags\Tag;

class ArticleController extends Controller
{
    private const int LIMIT = 20;

    public function __construct(
        private readonly ArticleListTransformer $transformer,
        private readonly CreateArticleCommand $createArticle,
        private readonly TagTransformer $tagTransformer,
    ) {
    }

    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $page = (int)$request->get('page', 1);

        $articles = Article::query()
            ->with('user')
            ->withRichText()
            ->when($search !== '', fn ($query, $search) => $query->whereFullText('title', $search))
            ->when($search !== '', fn ($query, $search) => $query->whereFullText('content', $search))
            ->orderBy('published_at', 'desc')
            ->paginate(self::LIMIT);

        return Inertia::render('Admin/Articles/Index', [
            'articles' => $this->transformer->transform($articles->items()),
            'paginate' => [
                'page' => $page,
                'total' => $articles->total(),
                'limit' => self::LIMIT,
                'onPage' => $articles->perPage(),
            ],
            'search' => $search,
            'activeSort' => 'published_at:desc',
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Runner::class);

        $tags = Tag::withType(TagType::ARTICLE_TAG->value)->get();
        $keywords = Tag::withType(TagType::ARTICLE_KEYWORDS->value)->get();

        return Inertia::render('Admin/Articles/Create', [
            'tags' => $tags->map(fn (Tag $tag) => $this->tagTransformer->transform($tag))->toArray(),
            'keywords' => $keywords->map(fn (Tag $tag) => $this->tagTransformer->transform($tag))->toArray(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $publishedAt = $data['publishedAt'] ? Carbon::createFromTimestampMs((int)$data['publishedAt']) : null;

            $this->createArticle->handle(new CreateArticle(
                title: $data['title'],
                content: $data['content'],
                publishedAt: $publishedAt,
                tags: $data['tags'] ?? [],
                keywords: $data['keywords'] ?? [],
                metaDescription: $data['metaDescription'] ?? '',
                user: $this->getUser(),
            ));

            $this->withMessage(self::ALERT_SUCCESS, trans('messages.article_created_success'));

            return redirect()->route('admin.articles.index');
        } catch (\Exception $e) {
            $this->withMessage(self::ALERT_ERROR, $e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
