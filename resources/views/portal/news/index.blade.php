<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>News | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">News</h1>
                        <p class="head-copy">Publish original programme notes that meet AdSense content standards: clear, useful, and written for people first.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>On the desk</span>
                            <strong>{{ $newsCount }} {{ \Illuminate\Support\Str::plural('note', $newsCount) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-stat-row">
                    <div class="news-stat"><span>Published</span><strong>{{ $publishedCount }}</strong></div>
                    <div class="news-stat"><span>Drafts</span><strong>{{ $draftCount }}</strong></div>
                    <div class="news-stat"><span>AdSense-ready length</span><strong>{{ $readyCount }}</strong></div>
                </div>

                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Journal">
                        <div class="panel-title">
                            <div>
                                <h2>Programme journal</h2>
                                <p>What you publish here is what Google reviews on the public news pages</p>
                            </div>
                            <a class="solid-btn" href="{{ route('portal.news.create') }}">Write a note</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        @if ($articles->isEmpty())
                            <div class="overview-empty">
                                <div>
                                    <span>Quiet journal</span>
                                    <strong>No notes on the desk yet.</strong>
                                    <span>Write original programme notes with clear titles, excerpts, and full articles.</span>
                                </div>
                            </div>
                        @else
                            <div class="news-ledger">
                                @foreach ($articles as $article)
                                    <a class="news-ledger-row" href="{{ route('portal.news.show', $article) }}">
                                        <img class="news-ledger-cover" src="{{ $article->imageUrl() }}" alt="{{ $article->image_alt }}">
                                        <div class="news-ledger-copy">
                                            <strong>{{ $article->title }}</strong>
                                            <span>{{ $article->published_at?->timezone(config('app.timezone'))->format('d M Y') }} · {{ $article->wordCount() }} words · {{ $article->slug }}</span>
                                        </div>
                                        <div class="news-ledger-meta">
                                            <span class="news-pill {{ $article->is_published ? 'is-live' : 'is-draft' }}">{{ $article->is_published ? 'Live' : 'Draft' }}</span>
                                            @if ($article->is_popular)
                                                <span class="news-pill is-popular">Popular</span>
                                            @endif
                                            @if ($article->isSubstantialForPublishing())
                                                <span class="news-pill is-live">Ready</span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="donors-pager">
                                {{ $articles->links() }}
                            </div>
                        @endif
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Quality">
                        <div class="panel-title">
                            <div>
                                <h2>AdSense checklist</h2>
                                <p>Publish for readers, not for ads</p>
                            </div>
                        </div>
                        <div class="news-quality">
                            <div class="news-quality-item">
                                <span class="news-quality-mark">1</span>
                                <div>
                                    <strong>Original writing</strong>
                                    <span>Use your own programme notes. Do not copy other sites or thin AI filler.</span>
                                </div>
                            </div>
                            <div class="news-quality-item">
                                <span class="news-quality-mark">2</span>
                                <div>
                                    <strong>Substantial articles</strong>
                                    <span>Aim for 180+ words with clear headings, practical guidance, and a real excerpt.</span>
                                </div>
                            </div>
                            <div class="news-quality-item">
                                <span class="news-quality-mark">3</span>
                                <div>
                                    <strong>Honest media</strong>
                                    <span>Use programme photos only. Never publish names or images of minors or survivors.</span>
                                </div>
                            </div>
                            <div class="news-quality-item">
                                <span class="news-quality-mark">4</span>
                                <div>
                                    <strong>Navigation &amp; policy</strong>
                                    <span>Public notes link to About, Contact, Privacy, and Report Live for a complete site.</span>
                                </div>
                            </div>
                        </div>
                        <div class="news-actions">
                            <a class="ghost-btn" href="{{ route('news') }}" target="_blank" rel="noopener">Open public journal</a>
                            <a class="ghost-btn" href="{{ route('privacy') }}" target="_blank" rel="noopener">Privacy policy</a>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
