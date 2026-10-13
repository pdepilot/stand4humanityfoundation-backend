<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $teamMember->name }} | Team</title>
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
<body data-page="team">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'team'])
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
                        <p class="eyebrow">Command · Public team</p>
                        <h1 class="head-title">{{ $teamMember->name }}</h1>
                        <p class="head-copy">{{ $teamMember->role }}</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface news-preview-card" data-stamp="Team">
                        <div class="panel-title">
                            <div>
                                <h2>Portrait</h2>
                                <p>Shown on the public site</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.team.edit', $teamMember) }}">Edit</a>
                            <a class="ghost-btn" href="{{ route('portal.team.index') }}">Back to the team</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        <div class="news-preview-shell">
                            <img class="news-preview-cover" src="{{ $teamMember->photoUrl() }}" alt="{{ $teamMember->name }}">
                            <div class="news-preview-body">
                                <h1>{{ $teamMember->name }}</h1>
                                <p>{{ $teamMember->role }}</p>
                            </div>
                        </div>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Desk">
                        <div class="panel-title">
                            <div>
                                <h2>Details</h2>
                                <p>Public profile</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('portal.team.destroy', $teamMember) }}" class="donors-destroy" onsubmit="return confirm('Remove this person from the team?');">
                            @csrf
                            @method('DELETE')
                            <button class="ghost-btn" type="submit">Remove from the team</button>
                        </form>

                        <dl class="settings-meta">
                            <div>
                                <dt>Name</dt>
                                <dd>{{ $teamMember->name }}</dd>
                            </div>
                            <div>
                                <dt>Position</dt>
                                <dd>{{ $teamMember->role }}</dd>
                            </div>
                            @foreach ($teamMember->socialLinks() as $link)
                                <div>
                                    <dt>{{ $link['label'] }}</dt>
                                    <dd><a href="{{ $link['url'] }}">{{ $link['url'] }}</a></dd>
                                </div>
                            @endforeach
                        </dl>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
