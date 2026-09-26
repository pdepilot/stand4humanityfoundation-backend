<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Record a Member | Stand For Humanity Foundation</title>
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
<body data-page="members">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'members'])
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
                        <p class="eyebrow">Command · Membership roll</p>
                        <h1 class="head-title">Record a member</h1>
                        <p class="head-copy">The desk assigns the member number when you save.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface settings-card news-form-card" data-stamp="Roll">
                        <div class="panel-title">
                            <div>
                                <h2>Member details</h2>
                                <p>Name, portrait, contact, and household</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.members.index') }}">Back to members</a>
                        </div>

                        <form method="POST" action="{{ route('portal.members.store') }}" class="settings-form" enctype="multipart/form-data">
                            @csrf
                            @include('portal.members._form')
                            <button class="settings-submit" type="submit">Save to the roll</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
