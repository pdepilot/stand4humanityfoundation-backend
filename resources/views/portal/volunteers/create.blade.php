<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Record a Volunteer | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Record an applicant</h1>
                        <p class="head-copy">Use the same fields as the public volunteer form.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="surface settings-card donors-card" data-stamp="Volunteer">
                    <div class="panel-title">
                        <div>
                            <h2>Volunteer details</h2>
                            <p>Matches the public volunteer application</p>
                        </div>
                        <a class="ghost-btn" href="{{ route('portal.volunteers.index') }}">Back to volunteer</a>
                    </div>

                    <form method="POST" action="{{ route('portal.volunteers.store') }}" class="settings-form" enctype="multipart/form-data" novalidate>
                        @csrf

                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="name">Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                            </div>
                            <div class="settings-field">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="phone">Phone</label>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required>
                            </div>
                            <div class="settings-field">
                                <label for="city">City / community</label>
                                <input id="city" name="city" type="text" value="{{ old('city', 'Owerri') }}" required>
                            </div>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="interest">Area of interest</label>
                                <select id="interest" name="interest" required>
                                    <option value="">Choose an area</option>
                                    @foreach (\App\Enums\VolunteerInterest::cases() as $interest)
                                        <option value="{{ $interest->value }}" @selected(old('interest') === $interest->value)>{{ $interest->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="settings-field">
                                <label for="availability">Availability</label>
                                <input id="availability" name="availability" type="text" value="{{ old('availability') }}" placeholder="Weekends, evenings" required>
                            </div>
                        </div>

                        <div class="settings-field">
                            <label for="photo">Applicant photo</label>
                            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp" required>
                        </div>

                        <div class="settings-field">
                            <label for="experience">Relevant experience</label>
                            <textarea id="experience" name="experience" rows="3">{{ old('experience') }}</textarea>
                        </div>

                        <div class="settings-field">
                            <label for="motivation">Why they want to volunteer</label>
                            <textarea id="motivation" name="motivation" rows="5" required>{{ old('motivation') }}</textarea>
                        </div>

                        <button class="settings-submit" type="submit">Save to the desk</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
