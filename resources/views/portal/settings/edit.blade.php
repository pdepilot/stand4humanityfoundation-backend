<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Settings | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <link href="{{ asset('images/portal/stand-logo.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/portal-command.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-settings.css') }}">
</head>
<body data-page="settings">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'settings'])
        <div class="stage">
            <header class="stage-head compact settings-hero">
                <div class="settings-media" aria-hidden="true">
                    <img src="{{ asset('images/portal/stand5.jpg') }}" alt="">
                    <div class="settings-multiply"></div>
                    <div class="settings-color"></div>
                    <div class="settings-gold"></div>
                </div>
                <div class="head-top">
                    <div>
                        <p class="eyebrow">Command · Admin settings</p>
                        <h1 class="head-title">Login details</h1>
                        <p class="head-copy">Change the email and password used to enter the staff portal. Both can be updated here.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>House keys</span>
                            <strong>{{ $user->role->label() }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace settings-workspace">
                <aside class="surface settings-identity" data-stamp="Desk">
                    <div class="panel-title">
                        <div>
                            <h2>The officer</h2>
                            <p>Signed in on the house desk</p>
                        </div>
                    </div>
                    <dl class="settings-meta">
                        <div>
                            <dt>Name</dt>
                            <dd>{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt>Present email</dt>
                            <dd>{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt>Role</dt>
                            <dd>{{ $user->role->label() }}</dd>
                        </div>
                    </dl>
                </aside>

                <div class="surface settings-card" data-stamp="Keys">
                    <div class="panel-title">
                        <div>
                            <h2>Portal credentials</h2>
                            <p>Confirm the current password before any change</p>
                        </div>
                    </div>

                    @if (session('status'))
                        <p class="settings-status" role="status">{{ session('status') }}</p>
                    @endif

                    <form method="POST" action="{{ route('portal.settings.update') }}" class="settings-form" novalidate>
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <div class="settings-field">
                            <label for="email">Login email</label>
                            <input id="email" name="email" type="email" autocomplete="username" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="settings-field">
                            <label for="current_password">Current password</label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="password">New password</label>
                                <input id="password" name="password" type="password" autocomplete="new-password">
                                <span>Leave blank to keep the current password.</span>
                            </div>
                            <div class="settings-field">
                                <label for="password_confirmation">Confirm new password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                            </div>
                        </div>

                        <button class="settings-submit" type="submit">Save login details</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
