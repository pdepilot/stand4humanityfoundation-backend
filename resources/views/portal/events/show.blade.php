<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $foundationEvent->title }} | Events</title>
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
                        <h1 class="head-title">{{ $foundationEvent->title }}</h1>
                        <p class="head-copy">{{ $foundationEvent->slug }} · {{ $foundationEvent->is_published ? 'Live on site' : 'Draft' }} · {{ $foundationEvent->cardDate() }}</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface news-preview-card" data-stamp="Preview">
                        <div class="panel-title">
                            <div>
                                <h2>Public preview</h2>
                                <p>How this gathering reads on the events page</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.events.index') }}">Back to events</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        <div class="news-preview-shell">
                            <img class="news-preview-cover" src="{{ $foundationEvent->imageUrl() }}" alt="{{ $foundationEvent->image_alt }}">
                            <div class="news-preview-body">
                                <p class="blog-card-meta">{{ $foundationEvent->featuredDate() }} · {{ $foundationEvent->timeRange() }}</p>
                                <h1>{{ $foundationEvent->title }}</h1>
                                <p>{{ $foundationEvent->description }}</p>
                                <p>{{ $foundationEvent->location }}</p>
                            </div>
                        </div>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Desk">
                        <div class="panel-title">
                            <div>
                                <h2>Calendar desk</h2>
                                <p>{{ $foundationEvent->timeRange() }}</p>
                            </div>
                        </div>

                        <dl class="settings-meta">
                            <div>
                                <dt>Status</dt>
                                <dd>{{ $foundationEvent->is_published ? 'Published' : 'Draft' }}</dd>
                            </div>
                            <div>
                                <dt>Featured</dt>
                                <dd>{{ $foundationEvent->is_featured ? 'Yes' : 'No' }}</dd>
                            </div>
                            <div>
                                <dt>Location</dt>
                                <dd>{{ $foundationEvent->location }}</dd>
                            </div>
                            <div>
                                <dt>Audience</dt>
                                <dd>{{ $foundationEvent->audience ?: '—' }}</dd>
                            </div>
                            <div>
                                <dt>Title link</dt>
                                <dd>{{ $foundationEvent->action->label() }}</dd>
                            </div>
                        </dl>

                        <div class="news-actions">
                            <a class="solid-btn" href="{{ route('portal.events.edit', $foundationEvent) }}">Edit gathering</a>
                            @if ($foundationEvent->is_published)
                                <a class="ghost-btn" href="{{ route('events') }}" target="_blank" rel="noopener">View on site</a>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('portal.events.destroy', $foundationEvent) }}" class="donors-destroy" onsubmit="return confirm('Remove this gathering from the calendar?');">
                            @csrf
                            @method('DELETE')
                            <button class="ghost-btn" type="submit">Remove gathering</button>
                        </form>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
