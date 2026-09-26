<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Donors | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Donors</h1>
                        <p class="head-copy">Gifts from the public donate page land here the moment they are recorded.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>On the ledger</span>
                            <strong>{{ $donorCount }} {{ \Illuminate\Support\Str::plural('gift', $donorCount) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="surface donors-card" data-stamp="Ledger">
                    <div class="panel-title">
                        <div>
                            <h2>Donor ledger</h2>
                            <p>Public donate form and desk entries share this book</p>
                        </div>
                        <a class="solid-btn" href="{{ route('portal.donors.create') }}">Record a gift</a>
                    </div>

                    @if (session('status'))
                        <p class="settings-status" role="status">{{ session('status') }}</p>
                    @endif

                    @if ($donations->isEmpty())
                        <div class="overview-empty">
                            <div>
                                <span>Quiet ledger</span>
                                <strong>No donors on the desk yet.</strong>
                                <span>A gift from the donate page will appear here.</span>
                            </div>
                        </div>
                    @else
                        <div class="donors-table-wrap">
                            <table class="donors-table">
                                <thead>
                                    <tr>
                                        <th>When</th>
                                        <th>Donor</th>
                                        <th>Cause</th>
                                        <th>Amount</th>
                                        <th>Source</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donations as $donation)
                                        <tr>
                                            <td>{{ $donation->created_at?->timezone(config('app.timezone'))->format('d M Y H:i') }}</td>
                                            <td>
                                                <strong>{{ $donation->name }}</strong>
                                                <span>{{ $donation->email }}{{ $donation->anonymous ? ' · Anonymous on the public ledger' : '' }}</span>
                                            </td>
                                            <td>{{ $donation->cause->label() }}</td>
                                            <td>{{ $donation->currency->value }} {{ number_format((float) $donation->amount, 0) }}</td>
                                            <td>{{ $donation->source->label() }}</td>
                                            <td><a href="{{ route('portal.donors.show', $donation) }}">Open</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="donors-pager">
                            {{ $donations->links() }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
