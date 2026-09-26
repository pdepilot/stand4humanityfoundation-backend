<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add Subscriber | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <link href="{{ asset('images/portal/stand-logo.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/portal-command.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-settings.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-donors.css') }}">
</head>
<body data-page="subscribers">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'list'])
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
                        <p class="eyebrow">Command · Newsletter desk</p>
                        <h1 class="head-title">Add an email</h1>
                        <p class="head-copy">Record a subscriber gathered offline or by phone.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="surface settings-card donors-card" data-stamp="Subscriber">
                    <div class="panel-title">
                        <div>
                            <h2>Subscriber details</h2>
                            <p>Email only · same book as the public form</p>
                        </div>
                        <a class="ghost-btn" href="{{ route('portal.subscribers.index') }}">Back to subscriber</a>
                    </div>

                    <form method="POST" action="{{ route('portal.subscribers.store') }}" class="settings-form" novalidate>
                        @csrf

                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <div class="settings-field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="120">
                        </div>

                        <button class="settings-submit" type="submit">Save to the list</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
