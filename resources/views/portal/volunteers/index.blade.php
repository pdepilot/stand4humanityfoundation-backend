<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Volunteer | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Volunteer</h1>
                        <p class="head-copy">Public volunteer applications land here for vetting before anyone joins the teams.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>Awaiting vetting</span>
                            <strong>{{ $pendingCount }} {{ \Illuminate\Support\Str::plural('application', $pendingCount) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="surface donors-card" data-stamp="Volunteer">
                    <div class="panel-title">
                        <div>
                            <h2>Volunteer ledger</h2>
                            <p>{{ $volunteerCount }} on the desk · approve only after conduct review</p>
                        </div>
                        <a class="solid-btn" href="{{ route('portal.volunteers.create') }}">Record an applicant</a>
                    </div>

                    @if (session('status'))
                        <p class="settings-status" role="status">{{ session('status') }}</p>
                    @endif

                    @if ($applications->isEmpty())
                        <div class="overview-empty">
                            <div>
                                <span>Quiet desk</span>
                                <strong>No volunteer applications yet.</strong>
                                <span>A request from the volunteer page will appear here for vetting.</span>
                            </div>
                        </div>
                    @else
                        <div class="donors-table-wrap">
                            <table class="donors-table">
                                <thead>
                                    <tr>
                                        <th>When</th>
                                        <th>Applicant</th>
                                        <th>Interest</th>
                                        <th>Status</th>
                                        <th>Source</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $application)
                                        <tr>
                                            <td>{{ $application->created_at?->timezone(config('app.timezone'))->format('d M Y H:i') }}</td>
                                            <td>
                                                <strong>{{ $application->name }}</strong>
                                                <span>{{ $application->email }} · {{ $application->city }}</span>
                                            </td>
                                            <td>{{ $application->interest->label() }}</td>
                                            <td>{{ $application->status->label() }}</td>
                                            <td>{{ $application->source->label() }}</td>
                                            <td><a href="{{ route('portal.volunteers.show', $application) }}">Vet</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="donors-pager">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
