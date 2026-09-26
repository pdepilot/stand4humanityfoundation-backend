<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Write a Note | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Write a note</h1>
                        <p class="head-copy">Draft a full article people can read. Short stubs and scraped text risk AdSense rejection.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface settings-card news-form-card" data-stamp="Compose">
                        <div class="panel-title">
                            <div>
                                <h2>Note details</h2>
                                <p>Markdown body · clear title · honest image alt text</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.news.index') }}">Back to news</a>
                        </div>

                        <form method="POST" action="{{ route('portal.news.store') }}" class="settings-form" novalidate data-news-form enctype="multipart/form-data">
                            @csrf
                            @include('portal.news._form', ['newsArticle' => null])
                            <button class="settings-submit" type="submit">Save to the journal</button>
                        </form>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Guide">
                        <div class="panel-title">
                            <div>
                                <h2>Before you publish</h2>
                                <p>Quality signals Google looks for</p>
                            </div>
                        </div>
                        <div class="news-quality">
                            <div class="news-quality-item">
                                <span class="news-quality-mark">A</span>
                                <div>
                                    <strong>One clear topic</strong>
                                    <span>Title and excerpt should match the article. Avoid vague or clickbait headings.</span>
                                </div>
                            </div>
                            <div class="news-quality-item">
                                <span class="news-quality-mark">B</span>
                                <div>
                                    <strong>Readable structure</strong>
                                    <span>Use Markdown headings, short paragraphs, and practical next steps.</span>
                                </div>
                            </div>
                            <div class="news-quality-item">
                                <span class="news-quality-mark">C</span>
                                <div>
                                    <strong>Keep drafts unpublished</strong>
                                    <span>Uncheck “Published” until the note is complete and reviewed.</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
    <script src="{{ asset('js/portal/news-desk.js') }}"></script>
</body>
</html>
