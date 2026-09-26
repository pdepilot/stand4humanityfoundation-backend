<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $contactMessage->name }} | Contact</title>
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
<body data-page="contacts">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'reach'])
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
                        <p class="eyebrow">Command · Contact desk</p>
                        <h1 class="head-title">{{ $contactMessage->name }}</h1>
                        <p class="head-copy">{{ $contactMessage->reference }} · {{ $contactMessage->source->label() }}</p>
                    </div>
                </div>
            </header>
            <main class="workspace settings-workspace">
                <aside class="surface settings-identity" data-stamp="Contact">
                    <div class="panel-title">
                        <div>
                            <h2>The request</h2>
                            <p>{{ $contactMessage->created_at?->timezone(config('app.timezone'))->format('d M Y · H:i') }}</p>
                        </div>
                    </div>
                    <dl class="settings-meta">
                        <div>
                            <dt>Assistance</dt>
                            <dd>{{ $contactMessage->subject }}</dd>
                        </div>
                        <div>
                            <dt>Status</dt>
                            <dd>{{ $contactMessage->status->label() }}</dd>
                        </div>
                    </dl>

                    <form method="POST" action="{{ route('portal.contacts.update', $contactMessage) }}" class="settings-form">
                        @csrf
                        @method('PUT')
                        <div class="settings-field">
                            <label for="status">Message status</label>
                            <select id="status" name="status" required>
                                @foreach (\App\Enums\ContactStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status', $contactMessage->status->value) === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="settings-submit" type="submit">Update status</button>
                    </form>
                </aside>

                <div class="surface settings-card" data-stamp="Record">
                    <div class="panel-title">
                        <div>
                            <h2>Contact record</h2>
                            <p>Visible in full on the desk</p>
                        </div>
                        <a class="ghost-btn" href="{{ route('portal.contacts.index') }}">Back to reach</a>
                    </div>

                    @if (session('status'))
                        <p class="settings-status" role="status">{{ session('status') }}</p>
                    @endif

                    @if ($errors->any())
                        <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                    @endif

                    <dl class="settings-meta">
                        <div>
                            <dt>Name</dt>
                            <dd>{{ $contactMessage->name }}</dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $contactMessage->email }}</dd>
                        </div>
                        <div>
                            <dt>Phone</dt>
                            <dd>{{ $contactMessage->phone ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Request</dt>
                            <dd>{{ $contactMessage->message }}</dd>
                        </div>
                    </dl>

                    <form method="POST" action="{{ route('portal.contacts.destroy', $contactMessage) }}" class="donors-destroy" onsubmit="return confirm('Remove this message from the desk?');">
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
