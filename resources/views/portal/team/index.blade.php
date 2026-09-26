<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Team | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Team</h1>
                        <p class="head-copy">People added here appear on the home page, the about page, and the volunteer page.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>On the site</span>
                            <strong>{{ $teamMembers->count() }} {{ \Illuminate\Support\Str::plural('person', $teamMembers->count()) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Team">
                        <div class="panel-title">
                            <div>
                                <h2>Public roster</h2>
                                <p>Add a name and a portrait, or remove someone from the site</p>
                            </div>
                            <a class="solid-btn" href="{{ route('portal.team.create') }}">Add a team member</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        @if ($teamMembers->isEmpty())
                            <div class="overview-empty">
                                <div>
                                    <span>Quiet roster</span>
                                    <strong>No one is on the public team yet.</strong>
                                    <span>Add a name, a role, and a portrait.</span>
                                </div>
                            </div>
                        @else
                            <div class="news-ledger">
                                @foreach ($teamMembers as $teamMember)
                                    <div class="news-ledger-row">
                                        <a href="{{ route('portal.team.show', $teamMember) }}">
                                            <img class="news-ledger-cover" src="{{ $teamMember->photoUrl() }}" alt="{{ $teamMember->name }}">
                                        </a>
                                        <a class="news-ledger-copy" href="{{ route('portal.team.show', $teamMember) }}">
                                            <strong>{{ $teamMember->name }}</strong>
                                            <span>{{ $teamMember->role }}</span>
                                        </a>
                                        <form method="POST" action="{{ route('portal.team.destroy', $teamMember) }}" onsubmit="return confirm('Remove this person from the team?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ghost-btn" type="submit">Remove</button>
                                        </form>
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
