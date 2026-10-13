<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add a Team Member | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Add a team member</h1>
                        <p class="head-copy">They appear on the home page, the about page, and the volunteer page.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface settings-card news-form-card" data-stamp="Team">
                        <div class="panel-title">
                            <div>
                                <h2>Team member</h2>
                                <p>Name, position, and portrait</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.team.index') }}">Back to the team</a>
                        </div>

                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('portal.team.store') }}" class="settings-form" enctype="multipart/form-data">
                            @csrf
                            @include('portal.team._form')
                            <button class="settings-submit" type="submit">Add to the team</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
