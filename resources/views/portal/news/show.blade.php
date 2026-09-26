<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $newsArticle->title }} | News</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <link href="{{ asset('images/portal/stand-logo.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/portal-command.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-settings.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-donors.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-news.css') }}">
</head>
<body data-page="news">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'news'])
        <div class="stage">
            <header class="stage-head compact settings-hero">
                <div class="settings-media" aria-hidden="true">
                    <img src="{{ asset('images/portal/stand4.jpg') }}" alt="">
                    <div class="settings-multiply"></div>
                    <div class="settings-color"></div>
                    <div class="settings-gold"></div>
                </div>
                <div class="head-top">
                    <div>
                        <p class="eyebrow">Command · Publisher desk</p>
                        <h1 class="head-title">{{ $newsArticle->title }}</h1>
                        <p class="head-copy">{{ $newsArticle->slug }} · {{ $newsArticle->is_published ? 'Live on site' : 'Draft' }} · {{ $newsArticle->wordCount() }} words</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface news-preview-card" data-stamp="Preview">
                        <div class="panel-title">
                            <div>
                                <h2>Reader preview</h2>
                                <p>How the public journal renders this note</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.news.index') }}">Back to news</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        <div class="news-preview-shell">
                            <img class="news-preview-cover" src="{{ $newsArticle->imageUrl() }}" alt="{{ $newsArticle->image_alt }}">
                            <div class="news-preview-body">
                                <p class="blog-card-meta">{{ $newsArticle->articleMetaLine() }}</p>
                                <h1>{{ $newsArticle->title }}</h1>
                                <p>{{ $newsArticle->excerpt }}</p>
                                <div class="blog-article-body">
                                    {!! $newsArticle->renderedBody() !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Desk">
                        <div class="panel-title">
                            <div>
                                <h2>Publishing desk</h2>
                                <p>{{ $newsArticle->published_at?->timezone(config('app.timezone'))->format('d M Y · H:i') }}</p>
                            </div>
                        </div>

                        <dl class="settings-meta">
                            <div>
                                <dt>Status</dt>
                                <dd>{{ $newsArticle->is_published ? 'Published' : 'Draft' }}</dd>
                            </div>
                            <div>
                                <dt>Popular</dt>
                                <dd>{{ $newsArticle->is_popular ? 'Yes' : 'No' }}</dd>
                            </div>
                            <div>
                                <dt>Word count</dt>
                                <dd>{{ $newsArticle->wordCount() }}</dd>
                            </div>
                            <div>
                                <dt>AdSense length</dt>
                                <dd>{{ $newsArticle->isSubstantialForPublishing() ? 'Ready' : 'Needs more substance' }}</dd>
                            </div>
                            <div>
                                <dt>Author</dt>
                                <dd>{{ $newsArticle->author }}</dd>
                            </div>
                            <div>
                                <dt>Tags</dt>
                                <dd>{{ $newsArticle->tagList() === [] ? '—' : implode(', ', $newsArticle->tagList()) }}</dd>
                            </div>
                        </dl>

                        <div class="news-actions">
                            <a class="solid-btn" href="{{ route('portal.news.edit', $newsArticle) }}">Edit note</a>
                            @if ($newsArticle->is_published)
                                <a class="ghost-btn" href="{{ route('news.show', $newsArticle->slug) }}" target="_blank" rel="noopener">View on site</a>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('portal.news.destroy', $newsArticle) }}" class="donors-destroy" onsubmit="return confirm('Remove this note from the journal?');">
                            @csrf
                            @method('DELETE')
                            <button class="ghost-btn" type="submit">Remove note</button>
                        </form>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
