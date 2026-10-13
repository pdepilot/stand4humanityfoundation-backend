<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Roles | Stand For Humanity Foundation</title>
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
<body data-page="roles">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'roles'])
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
                        <p class="eyebrow">Command · Super administrator</p>
                        <h1 class="head-title">Roles</h1>
                        <p class="head-copy">Create a role, choose the portal pages it may open, then assign it to an administrator.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Roles">
                        <div class="panel-title">
                            <div>
                                <h2>Administrator roles</h2>
                                <p>An administrator with no role may open every administrator page</p>
                            </div>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif
                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        @if ($roles->isEmpty())
                            <div class="overview-empty">
                                <div>
                                    <span>No roles</span>
                                    <strong>No administrator roles yet.</strong>
                                    <span>Create one and choose the pages it may open.</span>
                                </div>
                            </div>
                        @else
                            <div class="news-ledger">
                                @foreach ($roles as $role)
                                    <div class="news-ledger-row">
                                        <a class="news-ledger-copy" href="{{ route('portal.roles.edit', $role) }}">
                                            <strong>{{ $role->name }}</strong>
                                            <span>{{ $role->administrators_count }} {{ \Illuminate\Support\Str::plural('administrator', $role->administrators_count) }}</span>
                                        </a>
                                        <form method="POST" action="{{ route('portal.roles.destroy', $role) }}" onsubmit="return confirm('Remove this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ghost-btn" type="submit">Remove</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Add">
                        <div class="panel-title">
                            <div>
                                <h2>Add a role</h2>
                                <p>Name it, then tick the pages</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('portal.roles.store') }}" class="settings-form">
                            @csrf
                            @include('portal.roles._fields')
                            <button class="settings-submit" type="submit">Add role</button>
                        </form>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
