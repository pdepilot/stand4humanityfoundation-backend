<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $donation->name }} | Donors</title>
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
<body data-page="donors">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'donors'])
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
                        <p class="eyebrow">Command · Donor desk</p>
                        <h1 class="head-title">{{ $donation->name }}</h1>
                        <p class="head-copy">{{ $donation->reference }} · {{ $donation->source->label() }}</p>
                    </div>
                </div>
            </header>
            <main class="workspace settings-workspace">
                <aside class="surface settings-identity" data-stamp="Gift">
                    <div class="panel-title">
                        <div>
                            <h2>The gift</h2>
                            <p>{{ $donation->created_at?->timezone(config('app.timezone'))->format('d M Y · H:i') }}</p>
                        </div>
                    </div>
                    <dl class="settings-meta">
                        <div>
                            <dt>Amount</dt>
                            <dd>{{ $donation->currency->value }} {{ number_format((float) $donation->amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt>Cause</dt>
                            <dd>{{ $donation->cause->label() }}</dd>
                        </div>
                        <div>
                            <dt>Frequency</dt>
                            <dd>{{ $donation->frequency->label() }}</dd>
                        </div>
                        <div>
                            <dt>Payment</dt>
                            <dd>{{ $donation->payment_method->label() }}</dd>
                        </div>
                    </dl>
                </aside>

                <div class="surface settings-card" data-stamp="Donor">
                    <div class="panel-title">
                        <div>
                            <h2>Donor record</h2>
                            <p>Visible in full on the desk; the public ledger hides identity when asked</p>
                        </div>
                        <a class="ghost-btn" href="{{ route('portal.donors.index') }}">Back to donors</a>
                    </div>

                    @if (session('status'))
                        <p class="settings-status" role="status">{{ session('status') }}</p>
                    @endif

                    <dl class="settings-meta">
                        <div>
                            <dt>Legal name</dt>
                            <dd>{{ $donation->name }}</dd>
                        </div>
                        <div>
                            <dt>Public name</dt>
                            <dd>{{ $donation->publicName() }}</dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $donation->email }}</dd>
                        </div>
                        <div>
                            <dt>Phone</dt>
                            <dd>{{ $donation->phone }}</dd>
                        </div>
                        <div>
                            <dt>Location</dt>
                            <dd>{{ $donation->location ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Message</dt>
                            <dd>{{ $donation->description ?: '—' }}</dd>
                        </div>
                    </dl>

                    <form method="POST" action="{{ route('portal.donors.destroy', $donation) }}" class="donors-destroy" onsubmit="return confirm('Remove this donor record from the ledger?');">
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
