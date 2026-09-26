<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Display the public news journal.
     */
    public function index(Request $request): View
    {
        $articles = NewsArticle::query()
            ->published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        $popular = NewsArticle::query()
            ->published()
            ->popular()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        if ($popular->isEmpty()) {
            $popular = $articles->take(5);
        }

        return view('site.news', [
            'articles' => $articles,
            'popular' => $popular,
            'searchQuery' => (string) $request->string('q'),
        ]);
    }

    /**
     * Display a published journal article.
     */
    public function show(string $slug): View
    {
        abort_unless(preg_match('/^[a-z0-9-]+$/', $slug) === 1, 404);

        $article = NewsArticle::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $popular = NewsArticle::query()
            ->published()
            ->popular()
            ->whereKeyNot($article->id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        if ($popular->isEmpty()) {
            $popular = NewsArticle::query()
                ->published()
                ->whereKeyNot($article->id)
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(5)
                ->get();
        }

        return view('site.news.show', [
            'article' => $article,
            'popular' => $popular,
        ]);
    }
}
