<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cases | Stand For Humanity Foundation</title>
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
<body data-page="cases">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'cases'])
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
                        <p class="eyebrow">Command · Case desk</p>
                        <h1 class="head-title">Cases</h1>
                        <p class="head-copy">Reports from Report Live land here the moment they are received.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>On the desk</span>
                            <strong>{{ $caseCount }} {{ \Illuminate\Support\Str::plural('case', $caseCount) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="surface donors-card" data-stamp="Cases">
                    <div class="panel-title">
                        <div>
                            <h2>Case ledger</h2>
                            <p>Public Report Live and desk entries share this book</p>
                        </div>
                        <a class="solid-btn" href="{{ route('portal.cases.create') }}">Record a case</a>
                    </div>

                    @if (session('status'))
                        <p class="settings-status" role="status">{{ session('status') }}</p>
                    @endif

                    @if ($reports->isEmpty())
                        <div class="overview-empty">
                            <div>
                                <span>Quiet desk</span>
                                <strong>No cases on the desk yet.</strong>
                                <span>A filing from Report Live will appear here.</span>
                            </div>
                        </div>
                    @else
                        <div class="donors-table-wrap">
                            <table class="donors-table">
                                <thead>
                                    <tr>
                                        <th>When</th>
                                        <th>Reference</th>
                                        <th>Reporter</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Source</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>{{ $report->created_at?->timezone(config('app.timezone'))->format('d M Y H:i') }}</td>
                                            <td><strong>{{ $report->reference }}</strong></td>
                                            <td>
                                                <strong>{{ $report->reporterName() }}</strong>
                                                <span>{{ $report->incident_title }}</span>
                                            </td>
                                            <td>{{ $report->incident_type->label() }}</td>
                                            <td>{{ $report->status->label() }}</td>
                                            <td>{{ $report->source->label() }}</td>
                                            <td><a href="{{ route('portal.cases.show', $report) }}">Open</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="donors-pager">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
