<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Record a Case | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Record a case</h1>
                        <p class="head-copy">Use the same fields as Report Live. The case will appear on the public tracker by reference.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="surface settings-card donors-card" data-stamp="Case">
                    <div class="panel-title">
                        <div>
                            <h2>Case details</h2>
                            <p>Matches the public Report Live form</p>
                        </div>
                        <a class="ghost-btn" href="{{ route('portal.cases.index') }}">Back to cases</a>
                    </div>

                    <form method="POST" action="{{ route('portal.cases.store') }}" class="settings-form" enctype="multipart/form-data" novalidate>
                        @csrf

                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="full_name">Reporter name</label>
                                <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}">
                            </div>
                            <div class="settings-field">
                                <label for="phone">Phone</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
                            </div>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}">
                            </div>
                            <div class="settings-field">
                                <label for="relationship">Relationship</label>
                                <select id="relationship" name="relationship" required>
                                    <option value="" disabled @selected(! old('relationship'))>Select relationship</option>
                                    @foreach (\App\Enums\IncidentRelationship::cases() as $relationship)
                                        <option value="{{ $relationship->value }}" @selected(old('relationship') === $relationship->value)>{{ $relationship->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="incident_type">Incident type</label>
                                <select id="incident_type" name="incident_type" required>
                                    <option value="" disabled @selected(! old('incident_type'))>Select type</option>
                                    @foreach (\App\Enums\IncidentType::cases() as $type)
                                        <option value="{{ $type->value }}" @selected(old('incident_type') === $type->value)>{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="settings-field">
                                <label for="incident_title">Incident title</label>
                                <input id="incident_title" name="incident_title" type="text" value="{{ old('incident_title') }}" required>
                            </div>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="incident_date">Date of incident</label>
                                <input id="incident_date" name="incident_date" type="date" value="{{ old('incident_date') }}" required>
                            </div>
                            <div class="settings-field">
                                <label for="incident_time">Time of incident</label>
                                <input id="incident_time" name="incident_time" type="time" value="{{ old('incident_time') }}">
                            </div>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="state">State</label>
                                <select id="state" name="state" required>
                                    <option value="" disabled @selected(! old('state'))>Select state</option>
                                    @foreach (\App\Support\NigerianState::names() as $state)
                                        <option value="{{ $state }}" @selected(old('state', 'Imo') === $state)>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="settings-field">
                                <label for="lga">Local government area</label>
                                <input id="lga" name="lga" type="text" value="{{ old('lga') }}">
                            </div>
                        </div>

                        <div class="settings-field">
                            <label for="community">Community / town</label>
                            <input id="community" name="community" type="text" value="{{ old('community') }}">
                        </div>

                        <div class="settings-field">
                            <label for="full_address">Full address</label>
                            <input id="full_address" name="full_address" type="text" value="{{ old('full_address') }}">
                        </div>

                        <div class="settings-field">
                            <label for="description">Describe the incident</label>
                            <textarea id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="victim_name">Victim name</label>
                                <input id="victim_name" name="victim_name" type="text" value="{{ old('victim_name') }}">
                            </div>
                            <div class="settings-field">
                                <label for="victim_age">Victim age</label>
                                <input id="victim_age" name="victim_age" type="number" min="0" max="120" value="{{ old('victim_age') }}">
                            </div>
                        </div>

                        <div class="settings-field">
                            <label for="incident_address">Incident location</label>
                            <input id="incident_address" name="incident_address" type="text" value="{{ old('incident_address') }}">
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="latitude">GPS latitude</label>
                                <input id="latitude" name="latitude" type="text" inputmode="decimal" value="{{ old('latitude') }}">
                            </div>
                            <div class="settings-field">
                                <label for="longitude">GPS longitude</label>
                                <input id="longitude" name="longitude" type="text" inputmode="decimal" value="{{ old('longitude') }}">
                            </div>
                        </div>

                        <div class="settings-field">
                            <label for="evidence">Evidence files</label>
                            <input id="evidence" name="evidence[]" type="file" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.mp4,.mov,.webm,.avi,image/*,application/pdf,video/*">
                        </div>

                        <label class="donors-check">
                            <input type="checkbox" name="confirm_accurate" value="1" @checked(old('confirm_accurate'))>
                            I confirm the information provided is accurate to the best of my knowledge.
                        </label>
                        <label class="donors-check">
                            <input type="checkbox" name="confirm_false" value="1" @checked(old('confirm_false'))>
                            I understand false reporting may delay investigations and harm genuine victims.
                        </label>
                        <label class="donors-check">
                            <input type="checkbox" name="consent_processing" value="1" @checked(old('consent_processing'))>
                            I consent to Stand For Humanity Foundation processing this information for response and referral purposes.
                        </label>

                        <button class="settings-submit" type="submit">Save to the desk</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
