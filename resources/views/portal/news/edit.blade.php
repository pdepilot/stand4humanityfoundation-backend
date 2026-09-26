<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Note | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Edit note</h1>
                        <p class="head-copy">{{ $newsArticle->slug }} · keep the public article substantial and accurate</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface settings-card news-form-card" data-stamp="Compose">
                        <div class="panel-title">
                            <div>
                                <h2>Note details</h2>
                                <p>Changes sync to the public journal when published</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.news.show', $newsArticle) }}">Back to note</a>
                        </div>

                        <form method="POST" action="{{ route('portal.news.update', $newsArticle) }}" class="settings-form" novalidate data-news-form enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('portal.news._form', ['newsArticle' => $newsArticle])
                            <button class="settings-submit" type="submit">Update note</button>
                        </form>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Status">
                        <div class="panel-title">
                            <div>
                                <h2>Current signal</h2>
                                <p>Word count and readiness</p>
                            </div>
                        </div>
                        <div class="news-stat-row" style="grid-template-columns: 1fr;">
                            <div class="news-stat"><span>Words now</span><strong>{{ $newsArticle->wordCount() }}</strong></div>
                            <div class="news-stat"><span>Status</span><strong>{{ $newsArticle->is_published ? 'Live' : 'Draft' }}</strong></div>
                        </div>
                        <p class="news-form-hint">Target at least 180 words, a specific excerpt, and descriptive image alt text before setting the note live.</p>
                    </aside>
                </div>
            </main>
        </div>
    </div>
    <script src="{{ asset('js/portal/news-desk.js') }}"></script>
</body>
</html>
