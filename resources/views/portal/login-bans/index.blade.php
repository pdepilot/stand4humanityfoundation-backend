<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login blocks | Stand For Humanity Foundation</title>
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
<body data-page="bans">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'bans'])
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
                        <p class="eyebrow">Command · Sign-in desk</p>
                        <h1 class="head-title">Login blocks</h1>
                        <p class="head-copy">Lift a device block when the person should be allowed to sign in again.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Blocks">
                        <div class="panel-title">
                            <div>
                                <h2>Blocked devices</h2>
                                <p>Unban clears the address completely, including a permanent block</p>
                            </div>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        @if ($bans->isEmpty())
                            <div class="overview-empty">
                                <div>
                                    <span>Quiet gate</span>
                                    <strong>No devices are blocked.</strong>
                                </div>
                            </div>
                        @else
                            <div class="news-ledger">
                                @foreach ($bans as $ban)
                                    <div class="news-ledger-row">
                                        <div class="news-ledger-copy">
                                            <strong>{{ $ban->ip_address }}</strong>
                                            <span>{{ $ban->blockLabel() }}@if ($ban->banned_until) · until {{ $ban->banned_until->timezone('Africa/Lagos')->format('j M Y H:i') }} @endif</span>
                                        </div>
                                        <div class="news-ledger-meta">
                                            <form method="POST" action="{{ route('portal.login-bans.destroy', $ban) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="solid-btn" type="submit">Unban</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
