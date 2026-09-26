<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Meeting attendance | Stand For Humanity Foundation</title>
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
<body data-page="members">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'attendance'])
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
                        <p class="eyebrow">Command · Saturday meeting</p>
                        <h1 class="head-title">Attendance</h1>
                        <p class="head-copy">Mark members present on Saturday from 9:00 to 10:00. After 10:00 the roll closes. Three missed Saturdays in a row suspends a member.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Roll">
                        <div class="panel-title">
                            <div>
                                <h2>{{ $isOpen ? 'Roll is open' : 'Roll is closed' }}</h2>
                                <p>{{ $isOpen ? $meetingLabel.' · open until 10:00' : 'Marking opens again next Saturday from 9:00 to 10:00.' }}</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.members.index') }}">Members</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        @if ($members->isEmpty())
                            <div class="overview-empty">
                                <div>
                                    <span>Quiet roll</span>
                                    <strong>No active members to mark.</strong>
                                </div>
                            </div>
                        @else
                            <div class="news-ledger">
                                @foreach ($members as $member)
                                    <div class="news-ledger-row">
                                        <img class="news-ledger-cover" src="{{ $member->photoUrl() }}" alt="{{ $member->full_name }}">
                                        <div class="news-ledger-copy">
                                            <strong>{{ $member->full_name }}</strong>
                                            <span>{{ $member->member_number }} · {{ $member->phone }}</span>
                                        </div>
                                        <div class="news-ledger-meta">
                                            @if (in_array($member->id, $presentIds, true))
                                                <span class="news-pill is-live">Present</span>
                                            @elseif ($isOpen)
                                                <form method="POST" action="{{ route('portal.members.attendance.store') }}">
                                                    @csrf
                                                    <input type="hidden" name="foundation_member_id" value="{{ $member->id }}">
                                                    <button class="solid-btn" type="submit">Mark present</button>
                                                </form>
                                            @else
                                                <span class="news-pill is-draft">Closed</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
