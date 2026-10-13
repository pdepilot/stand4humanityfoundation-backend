<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Not permitted | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <link href="{{ asset('images/portal/stand-logo.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/portal-command.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-settings.css') }}">
</head>
<body data-page="forbidden">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail')
        <div class="stage">
            <header class="stage-head compact settings-hero">
                <div class="head-top">
                    <div>
                        <p class="eyebrow">Command · Access</p>
                        <h1 class="head-title">{{ \App\Enums\PortalPermission::DENIED_MESSAGE }}</h1>
                        <p class="head-copy">Ask a super administrator if this desk should be added to your role.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <a class="solid-btn" href="{{ route('portal.dashboard') }}">Return to the desk</a>
            </main>
        </div>
    </div>
</body>
</html>
