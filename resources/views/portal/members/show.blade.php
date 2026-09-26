<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $member->full_name }} | Members</title>
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
        @include('portal.partials.rail', ['active' => 'members'])
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
                        <p class="eyebrow">Command · Membership roll</p>
                        <h1 class="head-title">{{ $member->full_name }}</h1>
                        <p class="head-copy">{{ $member->member_number }} · {{ $member->status->label() }} · joined {{ $member->joined_on->format('j M Y') }}</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface news-preview-card" data-stamp="Record">
                        <div class="panel-title">
                            <div>
                                <h2>Member record</h2>
                                <p>{{ $member->city }}, {{ $member->state }}</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.members.index') }}">Back to members</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        <div class="news-preview-shell">
                            <img class="news-preview-cover" src="{{ $member->photoUrl() }}" alt="{{ $member->full_name }}">
                            <div class="news-preview-body">
                                <p class="blog-card-meta">{{ $member->member_number }}</p>
                                <h1>{{ $member->full_name }}</h1>
                                <p>{{ $member->occupation ?: 'Occupation not recorded' }}</p>
                                <p>{{ $member->address }}</p>
                            </div>
                        </div>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Desk">
                        <div class="panel-title">
                            <div>
                                <h2>Details</h2>
                                <p>{{ $member->gender->label() }}@if ($member->age()) · {{ $member->age() }} years @endif</p>
                            </div>
                            <a class="solid-btn" href="{{ route('portal.members.edit', $member) }}">Edit member</a>
                        </div>

                        @if ($member->status->value === 'suspended')
                            <form method="POST" action="{{ route('portal.members.suspend', $member) }}" class="donors-destroy" onsubmit="return confirm('Restore this member?');">
                                @csrf
                                @method('PUT')
                                <button class="ghost-btn" type="submit">Restore member</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('portal.members.suspend', $member) }}" class="donors-destroy" onsubmit="return confirm('Suspend this member?');">
                                @csrf
                                @method('PUT')
                                <button class="ghost-btn" type="submit">Suspend member</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('portal.members.destroy', $member) }}" class="donors-destroy" onsubmit="return confirm('Remove this member from the roll?');">
                            @csrf
                            @method('DELETE')
                            <button class="ghost-btn" type="submit">Delete member</button>
                        </form>

                        <dl class="settings-meta">
                            <div>
                                <dt>Member number</dt>
                                <dd>{{ $member->member_number }}</dd>
                            </div>
                            <div>
                                <dt>Status</dt>
                                <dd>{{ $member->status->label() }}</dd>
                            </div>
                            <div>
                                <dt>Date of birth</dt>
                                <dd>{{ $member->date_of_birth->format('j M Y') }}</dd>
                            </div>
                            <div>
                                <dt>Phone</dt>
                                <dd>{{ $member->phone }}</dd>
                            </div>
                            <div>
                                <dt>Email</dt>
                                <dd>{{ $member->email ?: 'Not recorded' }}</dd>
                            </div>
                            <div>
                                <dt>Emergency contact</dt>
                                <dd>{{ $member->emergency_contact_name ?: 'Not recorded' }}@if ($member->emergency_contact_phone) · {{ $member->emergency_contact_phone }}@endif</dd>
                            </div>
                            @if ($member->notes)
                                <div>
                                    <dt>Notes</dt>
                                    <dd>{{ $member->notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
