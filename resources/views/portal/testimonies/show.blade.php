<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $testimony->name }} | Testimonies</title>
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
<body data-page="testimonies">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'testimony'])
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
                        <p class="eyebrow">Command · Testimonies</p>
                        <h1 class="head-title">{{ $testimony->name }}</h1>
                        <p class="head-copy">{{ $testimony->status->label() }} · {{ $testimony->rating }} of 5</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface news-preview-card" data-stamp="Words">
                        <div class="panel-title">
                            <div>
                                <h2>Testimony</h2>
                                <p>{{ $testimony->role ?: 'Community' }}</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.testimonies.index') }}">Back to testimonies</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        <div class="news-preview-shell">
                            <img class="news-preview-cover" src="{{ $testimony->photoUrl() }}" alt="{{ $testimony->name }}">
                            <div class="news-preview-body">
                                <h1>{{ $testimony->name }}</h1>
                                <p>{{ $testimony->body }}</p>
                            </div>
                        </div>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Review">
                        <div class="panel-title">
                            <div>
                                <h2>Review</h2>
                                <p>{{ $testimony->status->label() }}</p>
                            </div>
                        </div>

                        @if ($testimony->status->value !== 'approved')
                            <form method="POST" action="{{ route('portal.testimonies.update', $testimony) }}" class="donors-destroy">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button class="solid-btn" type="submit">Approve</button>
                            </form>
                        @endif

                        @if ($testimony->status->value !== 'declined')
                            <form method="POST" action="{{ route('portal.testimonies.update', $testimony) }}" class="donors-destroy" onsubmit="return confirm('Decline this testimony?');">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="declined">
                                <button class="ghost-btn" type="submit">Decline</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('portal.testimonies.destroy', $testimony) }}" class="donors-destroy" onsubmit="return confirm('Remove this testimony?');">
                            @csrf
                            @method('DELETE')
                            <button class="ghost-btn" type="submit">Remove</button>
                        </form>

                        <dl class="settings-meta">
                            <div>
                                <dt>Name</dt>
                                <dd>{{ $testimony->name }}</dd>
                            </div>
                            <div>
                                <dt>Email</dt>
                                <dd>{{ $testimony->email ?: 'Not given' }}</dd>
                            </div>
                            <div>
                                <dt>Role</dt>
                                <dd>{{ $testimony->role ?: 'Not given' }}</dd>
                            </div>
                            <div>
                                <dt>Rating</dt>
                                <dd>{{ $testimony->rating }} of 5</dd>
                            </div>
                        </dl>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
