<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Record a Gift | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Record a gift</h1>
                        <p class="head-copy">Use the same fields as the public donate page. The gift will appear on the public ledger.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="surface settings-card donors-card" data-stamp="Gift">
                    <div class="panel-title">
                        <div>
                            <h2>Donation details</h2>
                            <p>Matches the public donate form</p>
                        </div>
                        <a class="ghost-btn" href="{{ route('portal.donors.index') }}">Back to donors</a>
                    </div>

                    <form method="POST" action="{{ route('portal.donors.store') }}" class="settings-form" novalidate>
                        @csrf

                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="name">Full name</label>
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
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required>
                            </div>
                            <div class="settings-field">
                                <label for="location">City / Country</label>
                                <input id="location" name="location" type="text" value="{{ old('location') }}">
                            </div>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="amount">Amount</label>
                                <input id="amount" name="amount" type="number" min="1" step="1" value="{{ old('amount') }}" required>
                            </div>
                            <div class="settings-field">
                                <label for="currency">Currency</label>
                                <select id="currency" name="currency" required>
                                    @foreach (\App\Enums\DonationCurrency::cases() as $currency)
                                        <option value="{{ $currency->value }}" @selected(old('currency', 'NGN') === $currency->value)>{{ $currency->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="settings-grid">
                            <div class="settings-field">
                                <label for="cause">Cause</label>
                                <select id="cause" name="cause" required>
                                    <option value="" disabled @selected(! old('cause'))>Select a cause</option>
                                    @foreach (\App\Enums\DonationCause::cases() as $cause)
                                        <option value="{{ $cause->value }}" @selected(old('cause') === $cause->value)>{{ $cause->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="settings-field">
                                <label for="frequency">Frequency</label>
                                <select id="frequency" name="frequency" required>
                                    @foreach (\App\Enums\DonationFrequency::cases() as $frequency)
                                        <option value="{{ $frequency->value }}" @selected(old('frequency', 'one-time') === $frequency->value)>{{ $frequency->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="settings-field">
                            <label for="payment_method">Payment method</label>
                            <select id="payment_method" name="payment_method" required>
                                @foreach (\App\Enums\DonationPaymentMethod::cases() as $method)
                                    <option value="{{ $method->value }}" @selected(old('payment_method', 'paystack') === $method->value)>{{ $method->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="settings-field">
                            <label for="description">Message / dedication</label>
                            <input id="description" name="description" type="text" value="{{ old('description') }}">
                        </div>

                        <label class="donors-check">
                            <input type="checkbox" name="anonymous" value="1" @checked(old('anonymous'))>
                            Make this donation anonymous on the public ledger
                        </label>

                        <button class="settings-submit" type="submit">Save to the ledger</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
