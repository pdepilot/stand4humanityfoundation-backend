<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Subscriber | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Subscriber</h1>
                        <p class="head-copy">Programme-note subscribers from the public site land here.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>Active</span>
                            <strong>{{ $activeCount }} {{ \Illuminate\Support\Str::plural('subscriber', $activeCount) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="surface donors-card" data-stamp="Subscriber">
                    <div class="panel-title">
                        <div>
                            <h2>Subscriber ledger</h2>
                            <p>{{ $subscriberCount }} on the book · active and unsubscribed</p>
                        </div>
                        <a class="solid-btn" href="{{ route('portal.subscribers.create') }}">Add an email</a>
                    </div>

                    @if (session('status'))
                        <p class="settings-status" role="status">{{ session('status') }}</p>
                    @endif

                    @if ($subscribers->isEmpty())
                        <div class="overview-empty">
                            <div>
                                <span>Quiet list</span>
                                <strong>No newsletter subscribers yet.</strong>
                                <span>A signup from the public site will appear here.</span>
                            </div>
                        </div>
                    @else
                        <div class="donors-table-wrap">
                            <table class="donors-table">
                                <thead>
                                    <tr>
                                        <th>When</th>
                                        <th>Email</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Source</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subscribers as $subscriber)
                                        <tr>
                                            <td>{{ $subscriber->created_at?->timezone(config('app.timezone'))->format('d M Y H:i') }}</td>
                                            <td><strong>{{ $subscriber->email }}</strong></td>
                                            <td>{{ $subscriber->locationLabel() }}</td>
                                            <td>{{ $subscriber->status->label() }}</td>
                                            <td>{{ $subscriber->source->label() }}</td>
                                            <td><a href="{{ route('portal.subscribers.show', $subscriber) }}">Open</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="donors-pager">
                            {{ $subscribers->links() }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
