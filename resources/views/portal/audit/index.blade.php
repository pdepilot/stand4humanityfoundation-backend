<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Audit exports | Stand For Humanity Foundation</title>
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
<body data-page="audit">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'audit'])
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
                        <p class="eyebrow">Command · Audit desk</p>
                        <h1 class="head-title">Export reports</h1>
                        <p class="head-copy">Choose a ledger and a date range. The desk downloads a CSV you can keep for an audit.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface settings-card news-form-card" data-stamp="Audit">
                        <div class="panel-title">
                            <div>
                                <h2>Generate an export</h2>
                                <p>Dates apply to when the record was saved. Attendance uses the meeting date. Mail uses the time it was sent.</p>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('portal.audit.store') }}" class="settings-form">
                            @csrf
                            <div class="settings-field">
                                <label for="report">Report</label>
                                <select id="report" name="report" required>
                                    <option value="" disabled @selected(! old('report'))>Choose a report</option>
                                    @foreach ($reports as $report)
                                        <option value="{{ $report->value }}" @selected(old('report') === $report->value)>{{ $report->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="settings-grid">
                                <div class="settings-field">
                                    <label for="from">From</label>
                                    <input id="from" name="from" type="date" value="{{ old('from') }}">
                                </div>
                                <div class="settings-field">
                                    <label for="to">To</label>
                                    <input id="to" name="to" type="date" value="{{ old('to') }}">
                                </div>
                            </div>
                            <p class="news-form-hint">Leave the dates empty to export the whole ledger.</p>
                            <button class="settings-submit" type="submit">Download CSV</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
