<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Members | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Members</h1>
                        <p class="head-copy">Record foundation members here. Look someone up by name or member number.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>On the roll</span>
                            <strong>{{ $memberCount }} {{ \Illuminate\Support\Str::plural('member', $memberCount) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-stat-row">
                    <div class="news-stat"><span>On the roll</span><strong>{{ $memberCount }}</strong></div>
                    <div class="news-stat"><span>Active</span><strong>{{ $activeCount }}</strong></div>
                    <div class="news-stat"><span>Shown</span><strong>{{ $members->total() }}</strong></div>
                </div>

                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Roll">
                        <div class="panel-title">
                            <div>
                                <h2>Membership roll</h2>
                                <p>Search a name or a member number such as SFH-00001</p>
                            </div>
                            <a class="solid-btn" href="{{ route('portal.members.create') }}">Record a member</a>
                        </div>

                        <form class="settings-form" method="GET" action="{{ route('portal.members.index') }}">
                            <div class="settings-field">
                                <label for="q">Find a member</label>
                                <input id="q" name="q" type="search" value="{{ $search }}" placeholder="Name or member number">
                            </div>
                            <button class="settings-submit" type="submit">Look up</button>
                        </form>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        @if ($members->isEmpty())
                            <div class="overview-empty">
                                <div>
                                    <span>{{ $search !== '' ? 'No match' : 'Quiet roll' }}</span>
                                    <strong>{{ $search !== '' ? 'No member matches that search.' : 'No members on the roll yet.' }}</strong>
                                    <span>{{ $search !== '' ? 'Try another name or the full member number.' : 'Add a name, a photo, and the rest of the record.' }}</span>
                                </div>
                            </div>
                        @else
                            <div class="news-ledger">
                                @foreach ($members as $member)
                                    <a class="news-ledger-row" href="{{ route('portal.members.show', $member) }}">
                                        <img class="news-ledger-cover" src="{{ $member->photoUrl() }}" alt="{{ $member->full_name }}">
                                        <div class="news-ledger-copy">
                                            <strong>{{ $member->full_name }}</strong>
                                            <span>{{ $member->member_number }} · {{ $member->city }} · {{ $member->phone }}</span>
                                        </div>
                                        <div class="news-ledger-meta">
                                            <span class="news-pill {{ $member->status->value === 'active' ? 'is-live' : 'is-draft' }}">{{ $member->status->label() }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <div class="donors-pager">
                                {{ $members->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
