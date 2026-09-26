<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Testimonies | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Testimonies</h1>
                        <p class="head-copy">Public submissions stay off the homepage until you approve them.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>Waiting</span>
                            <strong>{{ $pendingCount }} {{ \Illuminate\Support\Str::plural('review', $pendingCount) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Review">
                        <div class="panel-title">
                            <div>
                                <h2>Review queue</h2>
                                <p>Approve a testimony to place it on the homepage</p>
                            </div>
                            <a class="solid-btn" href="{{ route('portal.testimonies.create') }}">Publish a testimony</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        @if ($testimonies->isEmpty())
                            <div class="overview-empty">
                                <div>
                                    <span>Quiet desk</span>
                                    <strong>No testimonies yet.</strong>
                                    <span>Public submissions land here for review.</span>
                                </div>
                            </div>
                        @else
                            <div class="news-ledger">
                                @foreach ($testimonies as $testimony)
                                    <a class="news-ledger-row" href="{{ route('portal.testimonies.show', $testimony) }}">
                                        <img class="news-ledger-cover" src="{{ $testimony->photoUrl() }}" alt="{{ $testimony->name }}">
                                        <div class="news-ledger-copy">
                                            <strong>{{ $testimony->name }}</strong>
                                            <span>{{ $testimony->role ?: 'Community' }} · {{ \Illuminate\Support\Str::limit($testimony->body, 90) }}</span>
                                        </div>
                                        <div class="news-ledger-meta">
                                            <span class="news-pill {{ $testimony->status->value === 'approved' ? 'is-live' : 'is-draft' }}">{{ $testimony->status->label() }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="donors-pager">
                                {{ $testimonies->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
