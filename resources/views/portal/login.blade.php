<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Staff Portal | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <meta content="Sign in to the Stand For Humanity Foundation staff portal." name="description">
    <link href="{{ asset('images/portal/stand-logo.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/portal.css') }}" rel="stylesheet">
</head>
<body class="portal-login">
    <main class="login-shell">
        <section class="login-panel">
            <a class="login-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                <span class="brand-word">Stand For Humanity<br>Foundation</span>
            </a>
            <p class="login-kicker">Staff portal</p>
            <h1>Welcome back.</h1>
            <p class="login-lead">Sign in to stand with those who cannot stand alone — and continue the work from Owerri, Imo State.</p>

            <form method="POST" action="{{ route('portal.login.store') }}" class="login-form" novalidate>
                @csrf
                <div id="loginAlert" class="login-alert @if ($errors->any()) show @endif" role="alert">
                    @if ($errors->any())
                        {{ $errors->first() }}
                    @endif
                </div>
                <div class="field">
                    <input id="email" name="email" type="email" autocomplete="username" placeholder=" " value="{{ old('email') }}" required>
                    <label for="email">Email address</label>
                </div>
                <div class="field">
                    <input id="password" name="password" type="password" autocomplete="current-password" placeholder=" " required>
                    <label for="password">Password</label>
                    <button class="field-toggle" type="button" id="togglePassword" aria-label="Show password">
                        <i class="fa fa-eye"></i>
                    </button>
                </div>
                <div class="login-row">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        Remember me
                    </label>
                    <a href="mailto:{{ config('portal.contact_email') }}">Need access?</a>
                </div>
                <button class="login-submit" id="loginSubmit" type="submit">Enter portal</button>
            </form>
            <p class="login-foot">Protected work space · <a href="{{ route('home') }}">Return to the public site</a></p>
        </section>

        <aside class="login-visual @if (request()->query('from') === 'logout') from-logout @endif" id="loginVisual" aria-hidden="true" style="--wash: {{ $loginVisual['wash'] }}; --wash-soft: {{ $loginVisual['soft'] }}; --accent: {{ $loginVisual['accent'] }};">
            <img id="loginVisualImg" src="{{ asset($loginVisual['image']) }}" alt="">
            <div class="login-visual-multiply"></div>
            <div class="login-visual-color"></div>
            <div class="login-visual-copy">
                <p>We stand with those who cannot stand alone.</p>
                <span>Owerri, Imo State, Nigeria</span>
            </div>
        </aside>
    </main>

    <script src="{{ asset('js/portal/login.js') }}"></script>
</body>
</html>
