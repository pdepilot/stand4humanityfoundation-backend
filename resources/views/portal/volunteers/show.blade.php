<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $volunteerApplication->name }} | Volunteer</title>
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
<body data-page="volunteers">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'hands'])
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
                        <p class="eyebrow">Command · Volunteer desk</p>
                        <h1 class="head-title">{{ $volunteerApplication->name }}</h1>
                        <p class="head-copy">{{ $volunteerApplication->reference }} · {{ $volunteerApplication->source->label() }}</p>
                    </div>
                </div>
            </header>
            <main class="workspace settings-workspace">
                <aside class="surface settings-identity" data-stamp="Vet">
                    <div class="panel-title">
                        <div>
                            <h2>Vetting</h2>
                            <p>{{ $volunteerApplication->created_at?->timezone(config('app.timezone'))->format('d M Y · H:i') }}</p>
                        </div>
                    </div>
                    <dl class="settings-meta">
                        <div>
                            <dt>Interest</dt>
                            <dd>{{ $volunteerApplication->interest->label() }}</dd>
                        </div>
                        <div>
                            <dt>Status</dt>
                            <dd>{{ $volunteerApplication->status->label() }}</dd>
                        </div>
                        @if ($volunteerApplication->reviewed_at)
                            <div>
                                <dt>Reviewed</dt>
                                <dd>{{ $volunteerApplication->reviewed_at->timezone(config('app.timezone'))->format('d M Y · H:i') }}</dd>
                            </div>
                        @endif
                    </dl>

                    <form method="POST" action="{{ route('portal.volunteers.update', $volunteerApplication) }}" class="settings-form">
                        @csrf
                        @method('PUT')
                        <div class="settings-field">
                            <label for="status">Vetting status</label>
                            <select id="status" name="status" required>
                                @foreach (\App\Enums\VolunteerStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status', $volunteerApplication->status->value) === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="settings-field">
                            <label for="admin_notes">Desk notes</label>
                            <textarea id="admin_notes" name="admin_notes" rows="4" placeholder="Conduct checks, interview notes, next steps">{{ old('admin_notes', $volunteerApplication->admin_notes) }}</textarea>
                        </div>
                        <button class="settings-submit" type="submit">Update vetting</button>
                    </form>
                </aside>

                <div class="surface settings-card" data-stamp="Record">
                    <div class="panel-title">
                        <div>
                            <h2>Application record</h2>
                            <p>Visible in full on the desk</p>
                        </div>
                        <a class="ghost-btn" href="{{ route('portal.volunteers.index') }}">Back to volunteer</a>
                    </div>

                    @if (session('status'))
                        <p class="settings-status" role="status">{{ session('status') }}</p>
                    @endif

                    @if ($errors->any())
                        <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                    @endif

                    <dl class="settings-meta">
                        <div>
                            <dt>Photo</dt>
                            <dd>
                                <img src="{{ $volunteerApplication->photoUrl() }}" alt="Photo of {{ $volunteerApplication->name }}" style="max-width: 220px; height: auto; border-radius: 4px;">
                            </dd>
                        </div>
                        <div>
                            <dt>Name</dt>
                            <dd>{{ $volunteerApplication->name }}</dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $volunteerApplication->email }}</dd>
                        </div>
                        <div>
                            <dt>Phone</dt>
                            <dd>{{ $volunteerApplication->phone }}</dd>
                        </div>
                        <div>
                            <dt>City</dt>
                            <dd>{{ $volunteerApplication->city }}</dd>
                        </div>
                        <div>
                            <dt>Availability</dt>
                            <dd>{{ $volunteerApplication->availability }}</dd>
                        </div>
                        <div>
                            <dt>Experience</dt>
                            <dd>{{ $volunteerApplication->experience ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Motivation</dt>
                            <dd>{{ $volunteerApplication->motivation }}</dd>
                        </div>
                    </dl>

                    <form method="POST" action="{{ route('portal.volunteers.destroy', $volunteerApplication) }}" class="donors-destroy" onsubmit="return confirm('Remove this volunteer application from the desk?');">
                        @csrf
                        @method('DELETE')
                        <button class="ghost-btn" type="submit">Remove record</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
