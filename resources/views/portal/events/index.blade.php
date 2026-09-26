<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Events | Stand For Humanity Foundation</title>
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
<body data-page="events">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'events'])
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
                        <p class="eyebrow">Command · Calendar desk</p>
                        <h1 class="head-title">Events</h1>
                        <p class="head-copy">Schedule gatherings here. Published events appear on the public events page.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>On the calendar</span>
                            <strong>{{ $eventCount }} {{ \Illuminate\Support\Str::plural('event', $eventCount) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-stat-row">
                    <div class="news-stat"><span>Published</span><strong>{{ $publishedCount }}</strong></div>
                    <div class="news-stat"><span>Drafts</span><strong>{{ $draftCount }}</strong></div>
                    <div class="news-stat"><span>Featured</span><strong>{{ $featuredCount }}</strong></div>
                </div>

                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Calendar">
                        <div class="panel-title">
                            <div>
                                <h2>Gatherings</h2>
                                <p>What you publish here is what visitors see on the events page</p>
                            </div>
                            <a class="solid-btn" href="{{ route('portal.events.create') }}">Schedule a gathering</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        @if ($events->isEmpty())
                            <div class="overview-empty">
                                <div>
                                    <span>Quiet calendar</span>
                                    <strong>No gatherings on the desk yet.</strong>
                                    <span>Add a title, a time, a place, and a short description.</span>
                                </div>
                            </div>
                        @else
                            <div class="news-ledger">
                                @foreach ($events as $event)
                                    <a class="news-ledger-row" href="{{ route('portal.events.show', $event) }}">
                                        <img class="news-ledger-cover" src="{{ $event->imageUrl() }}" alt="{{ $event->image_alt }}">
                                        <div class="news-ledger-copy">
                                            <strong>{{ $event->title }}</strong>
                                            <span>{{ $event->cardDate() }} · {{ $event->timeRange() }} · {{ $event->location }}</span>
                                        </div>
                                        <div class="news-ledger-meta">
                                            <span class="news-pill {{ $event->is_published ? 'is-live' : 'is-draft' }}">{{ $event->is_published ? 'Live' : 'Draft' }}</span>
                                            @if ($event->is_featured)
                                                <span class="news-pill is-popular">Featured</span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="donors-pager">
                                {{ $events->links() }}
                            </div>
                        @endif
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Public">
                        <div class="panel-title">
                            <div>
                                <h2>Public page</h2>
                                <p>One featured gathering leads the page</p>
                            </div>
                        </div>
                        <div class="news-quality">
                            <div class="news-quality-item">
                                <span class="news-quality-mark">1</span>
                                <div>
                                    <strong>Featured slot</strong>
                                    <span>The featured gathering is the large story at the top of the events page.</span>
                                </div>
                            </div>
                            <div class="news-quality-item">
                                <span class="news-quality-mark">2</span>
                                <div>
                                    <strong>Upcoming cards</strong>
                                    <span>Every other published gathering appears in the card grid, ordered by start time.</span>
                                </div>
                            </div>
                            <div class="news-quality-item">
                                <span class="news-quality-mark">3</span>
                                <div>
                                    <strong>Drafts stay private</strong>
                                    <span>Unpublished gatherings remain on this desk and do not appear on the site.</span>
                                </div>
                            </div>
                        </div>
                        <div class="news-actions">
                            <a class="ghost-btn" href="{{ route('events') }}" target="_blank" rel="noopener">Open public events</a>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
