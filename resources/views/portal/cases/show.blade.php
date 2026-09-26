<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $incidentReport->reference }} | Cases</title>
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
                        <h1 class="head-title">{{ $incidentReport->reference }}</h1>
                        <p class="head-copy">{{ $incidentReport->status->label() }} · {{ $incidentReport->source->label() }}</p>
                    </div>
                </div>
            </header>
            <main class="workspace settings-workspace">
                <aside class="surface settings-identity" data-stamp="Case">
                    <div class="panel-title">
                        <div>
                            <h2>The filing</h2>
                            <p>{{ $incidentReport->created_at?->timezone(config('app.timezone'))->format('d M Y · H:i') }}</p>
                        </div>
                    </div>
                    <dl class="settings-meta">
                        <div>
                            <dt>Type</dt>
                            <dd>{{ $incidentReport->incident_type->label() }}</dd>
                        </div>
                        <div>
                            <dt>Title</dt>
                            <dd>{{ $incidentReport->incident_title }}</dd>
                        </div>
                        <div>
                            <dt>When</dt>
                            <dd>{{ $incidentReport->incident_date?->format('d M Y') }}{{ $incidentReport->incident_time ? ' · '.$incidentReport->incident_time : '' }}</dd>
                        </div>
                        <div>
                            <dt>State</dt>
                            <dd>{{ $incidentReport->state }}</dd>
                        </div>
                    </dl>

                    <form method="POST" action="{{ route('portal.cases.update', $incidentReport) }}" class="settings-form">
                        @csrf
                        @method('PUT')
                        <div class="settings-field">
                            <label for="status">Case status</label>
                            <select id="status" name="status" required>
                                @foreach (\App\Enums\IncidentStatus::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(old('status', $incidentReport->status->value) === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="settings-submit" type="submit">Update status</button>
                    </form>
                </aside>

                <div class="surface settings-card" data-stamp="Record">
                    <div class="panel-title">
                        <div>
                            <h2>Case record</h2>
                            <p>Visible in full on the desk; the public tracker shows only the reference and status</p>
                        </div>
                        <a class="ghost-btn" href="{{ route('portal.cases.index') }}">Back to cases</a>
                    </div>

                    @if (session('status'))
                        <p class="settings-status" role="status">{{ session('status') }}</p>
                    @endif

                    @if ($errors->any())
                        <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                    @endif

                    <dl class="settings-meta">
                        <div>
                            <dt>Reporter</dt>
                            <dd>{{ $incidentReport->reporterName() }}</dd>
                        </div>
                        <div>
                            <dt>Legal name</dt>
                            <dd>{{ $incidentReport->full_name ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Phone</dt>
                            <dd>{{ $incidentReport->phone ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $incidentReport->email ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Relationship</dt>
                            <dd>{{ $incidentReport->relationship->label() }}</dd>
                        </div>
                        <div>
                            <dt>LGA</dt>
                            <dd>{{ $incidentReport->lga ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Community</dt>
                            <dd>{{ $incidentReport->community ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Address</dt>
                            <dd>{{ $incidentReport->full_address ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Incident location</dt>
                            <dd>{{ $incidentReport->incident_address ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>GPS</dt>
                            <dd>{{ $incidentReport->coordinatesLabel() ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt>Description</dt>
                            <dd>{{ $incidentReport->description }}</dd>
                        </div>
                        <div>
                            <dt>Victim</dt>
                            <dd>{{ $incidentReport->victim_name ?: '—' }}{{ $incidentReport->victim_age !== null ? ' · '.$incidentReport->victim_age : '' }}{{ $incidentReport->victim_gender ? ' · '.$incidentReport->victim_gender->label() : '' }}</dd>
                        </div>
                        <div>
                            <dt>Suspect</dt>
                            <dd>{{ $incidentReport->suspect_name ?: '—' }}</dd>
                        </div>
                    </dl>

                    @if ($incidentReport->mapEmbedUrl())
                        <div class="case-map">
                            <iframe
                                src="{{ $incidentReport->mapEmbedUrl() }}"
                                title="Captured incident location"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    @endif

                    @if ($incidentReport->files->isNotEmpty())
                        <div class="panel-title">
                            <div>
                                <h2>Evidence</h2>
                                <p>Private files stored off the public site</p>
                            </div>
                        </div>
                        <ul class="settings-meta">
                            @foreach ($incidentReport->files as $file)
                                <li>
                                    <a href="{{ route('portal.cases.files.download', [$incidentReport, $file]) }}">{{ $file->original_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('portal.cases.destroy', $incidentReport) }}" class="donors-destroy" onsubmit="return confirm('Remove this case from the desk?');">
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
