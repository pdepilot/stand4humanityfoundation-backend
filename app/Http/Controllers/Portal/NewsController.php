<?php

namespace App\Http\Controllers\Portal;

use App\Actions\StoreNewsArticleImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreNewsArticleRequest;
use App\Http\Requests\Portal\UpdateNewsArticleRequest;
use App\Models\NewsArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Display the command-desk news ledger.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', NewsArticle::class);

        $articles = NewsArticle::query()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $allArticles = NewsArticle::query()->get(['body', 'excerpt', 'image_alt', 'is_published']);

        return view('portal.news.index', [
            'user' => $request->user(),
            'articles' => $articles,
            'newsCount' => $articles->total(),
            'publishedCount' => $allArticles->where('is_published', true)->count(),
            'draftCount' => $allArticles->where('is_published', false)->count(),
            'readyCount' => $allArticles->filter(fn (NewsArticle $article): bool => $article->isSubstantialForPublishing())->count(),
        ]);
    }

    /**
     * Show the form for writing a journal note.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', NewsArticle::class);

        return view('portal.news.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Store a journal note from the desk.
     */
    public function store(StoreNewsArticleRequest $request, StoreNewsArticleImageAction $storeNewsArticleImage): RedirectResponse
    {
        $details = $request->articleDetails();
        $details['image'] = $storeNewsArticleImage->store($request->uploadedImage());

        $article = NewsArticle::query()->create($details);

        return redirect()
            ->route('portal.news.show', $article)
            ->with('status', 'The note is on the journal.');
    }

    /**
     * Display a journal note on the desk.
     */
    public function show(Request $request, NewsArticle $newsArticle): View
    {
        Gate::authorize('view', $newsArticle);

        return view('portal.news.show', [
            'user' => $request->user(),
            'newsArticle' => $newsArticle,
        ]);
    }

    /**
     * Show the form for editing a journal note.
     */
    public function edit(Request $request, NewsArticle $newsArticle): View
    {
        Gate::authorize('update', $newsArticle);

        return view('portal.news.edit', [
            'user' => $request->user(),
            'newsArticle' => $newsArticle,
        ]);
    }

    /**
     * Update a journal note.
     */
    public function update(
        UpdateNewsArticleRequest $request,
        NewsArticle $newsArticle,
        StoreNewsArticleImageAction $storeNewsArticleImage,
    ): RedirectResponse {
        $details = $request->articleDetails();
        $uploadedImage = $request->uploadedImage();

        if ($uploadedImage !== null) {
            $previousImage = $newsArticle->image;
            $details['image'] = $storeNewsArticleImage->store($uploadedImage);
            $storeNewsArticleImage->delete($previousImage);
        }

        $newsArticle->update($details);

        return redirect()
            ->route('portal.news.show', $newsArticle)
            ->with('status', 'The note was updated.');
    }

    /**
     * Remove a journal note.
     */
    public function destroy(NewsArticle $newsArticle, StoreNewsArticleImageAction $storeNewsArticleImage): RedirectResponse
    {
        Gate::authorize('delete', $newsArticle);

        $imagePath = $newsArticle->image;
        $newsArticle->delete();
        $storeNewsArticleImage->delete($imagePath);

        return redirect()
            ->route('portal.news.index')
            ->with('status', 'The note was removed from the journal.');
    }
}
