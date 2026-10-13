<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Administrators | Stand For Humanity Foundation</title>
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
<body data-page="admins">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'admins'])
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
                        <h1 class="head-title">Administrators</h1>
                        <p class="head-copy">Add an administrator, assign a role, suspend their access, or remove the account. A blank role leaves every administrator page open.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Roster">
                        <div class="panel-title">
                            <div>
                                <h2>Admin roster</h2>
                                <p>Suspended accounts cannot sign in</p>
                            </div>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        <div class="news-ledger">
                            @foreach ($administrators as $administrator)
                                <div class="news-ledger-row">
                                    <div class="news-ledger-copy">
                                        <strong>{{ $administrator->name }}</strong>
                                        <span>{{ $administrator->email }} · {{ $administrator->adminRole?->name ?? 'Every administrator page' }}</span>
                                    </div>
                                    <div class="news-ledger-meta">
                                        @if ($administrator->isSuperAdministrator())
                                            <span class="news-pill is-popular">Super administrator</span>
                                        @elseif ($administrator->isSuspended())
                                            <span class="news-pill is-draft">Suspended</span>
                                        @else
                                            <span class="news-pill is-live">Active</span>
                                        @endif
                                    </div>
                                    @if ($user->can('assignRole', $administrator))
                                        <form method="POST" action="{{ route('portal.admins.role', $administrator) }}">
                                            @csrf
                                            @method('PUT')
                                            <select name="admin_role_id" aria-label="Role for {{ $administrator->name }}">
                                                <option value="">Every administrator page</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}" @selected((string) $administrator->admin_role_id === (string) $role->id)>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <button class="ghost-btn" type="submit">Assign role</button>
                                        </form>
                                    @endif
                                    @if ($user->can('suspend', $administrator))
                                        <form method="POST" action="{{ route('portal.admins.update', $administrator) }}">
                                            @csrf
                                            @method('PUT')
                                            <button class="ghost-btn" type="submit">{{ $administrator->isSuspended() ? 'Restore' : 'Suspend' }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('portal.admins.destroy', $administrator) }}" onsubmit="return confirm('Remove this administrator?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ghost-btn" type="submit">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Add">
                        <div class="panel-title">
                            <div>
                                <h2>Add administrator</h2>
                                <p>They can use the portal desks</p>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('portal.admins.store') }}" class="settings-form">
                            @csrf
                            <div class="settings-field">
                                <label for="name">Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                            </div>
                            <div class="settings-field">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                            </div>
                            <div class="settings-field">
                                <label for="password">Password</label>
                                <input id="password" name="password" type="password" required autocomplete="new-password">
                            </div>
                            <div class="settings-field">
                                <label for="password_confirmation">Confirm password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
                            </div>
                            <div class="settings-field">
                                <label for="admin_role_id">Role</label>
                                <select id="admin_role_id" name="admin_role_id">
                                    <option value="">Every administrator page</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected((string) old('admin_role_id') === (string) $role->id)>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="settings-submit" type="submit">Add administrator</button>
                        </form>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
