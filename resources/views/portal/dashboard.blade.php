<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Command Desk | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <link href="{{ asset('images/portal/stand-logo.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/portal-command.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-overview.css') }}">
</head>
<body data-page="dashboard">
    <div class="overview-rule" data-overview-rule></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'desk'])
        <div class="stage">
            <header class="stage-head overview-hero">
                <div class="overview-media" aria-hidden="true">
                    <img class="overview-photo" src="{{ asset('images/portal/stand2.jpg') }}" alt="">
                    <div class="overview-multiply"></div>
                    <div class="overview-color"></div>
                    <div class="overview-gold"></div>
                    <div class="overview-grain"></div>
                </div>
                <div class="overview-orbit" aria-hidden="true"></div>
                <div class="head-top overview-lead">
                    <div>
                        <p class="eyebrow">Command · The house desk</p>
                        <p class="overview-kicker">Owerri · Imo State · Nigeria</p>
                        <h1 class="head-title overview-title">The stand<br><em>is open.</em></h1>
                        <p class="head-copy" data-greeting data-name="{{ $user->name }}">The stand is open.</p>
                        <p class="overview-vow">We stand with those who cannot stand alone.</p>
                    </div>
                    <div class="overview-aside">
                        <div class="live-pill"><i class="live-dot"></i> <span data-clock>00:00:00</span> · Desk open</div>
                        <div class="overview-seal">
                            <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                            <span>House seal</span>
                        </div>
                    </div>
                </div>
                <div class="metric-rail overview-metrics">
                    <article class="metric"><span class="label">Staff</span><span class="value" data-count="{{ $staffCount }}">0</span><span class="delta">{{ $user->role->label() }}</span></article>
                    @if ($user->canOpenPortal(\App\Enums\PortalPermission::Donors))
                        <a class="metric" href="{{ route('portal.donors.index') }}"><span class="label">Donors</span><span class="value" data-count="{{ $donorCount }}">0</span><span class="delta">{{ $donorCount === 1 ? 'Gift on the ledger' : 'Open the ledger' }}</span></a>
                    @endif
                    @if ($user->canOpenPortal(\App\Enums\PortalPermission::Cases))
                        <a class="metric" href="{{ route('portal.cases.index') }}"><span class="label">Cases</span><span class="value" data-count="{{ $caseCount }}">0</span><span class="delta">Report Live</span></a>
                    @endif
                    @if ($user->canOpenPortal(\App\Enums\PortalPermission::Contacts))
                        <a class="metric" href="{{ route('portal.contacts.index') }}"><span class="label">Contact</span><span class="value" data-count="{{ $contactCount }}">0</span><span class="delta">Contact desk</span></a>
                    @endif
                    @if ($user->canOpenPortal(\App\Enums\PortalPermission::News))
                        <a class="metric" href="{{ route('portal.news.index') }}"><span class="label">Notes</span><span class="value" data-count="{{ $newsCount }}">0</span><span class="delta">Programme journal</span></a>
                    @endif
                    <article class="metric"><span class="label">Goal</span><span class="value">&#8358;{{ $donationProgress->formattedGoal() }}</span><span class="delta">{{ $donationProgress->percent }}% raised</span></article>
                    <article class="metric"><span class="label">Desk</span><span class="value">Open</span><span class="delta">Owerri</span></article>
                </div>
            </header>
            <main class="workspace">
                <div class="ribbon" aria-label="Live foundation activity">
                    <div class="ribbon-label">Pulse</div>
                    <div class="ticker">
                        <p class="ticker-track">
                            <span>The stand is open in <b>Owerri, Imo State</b>.</span>
                            <span>We stand with those who cannot stand alone.</span>
                            <span>Child protection, rescue, and victim support remain on the desk.</span>
                            <span>The stand is open in <b>Owerri, Imo State</b>.</span>
                            <span>We stand with those who cannot stand alone.</span>
                            <span>Child protection, rescue, and victim support remain on the desk.</span>
                        </p>
                    </div>
                </div>
                <div class="surface goal-desk" data-stamp="Goal">
                    <div class="panel-title">
                        <div>
                            <h2>Donation goals</h2>
                            <p>Set a public target for each programme card, and the amount already spent from the ledger.</p>
                        </div>
                    </div>
                    @if (session('status'))
                        <p class="goal-status" role="status">{{ session('status') }}</p>
                    @endif
                    @php($goalError = collect($errors->keys())->first(fn (string $key): bool => ! str_starts_with($key, 'gallery_')))
                    @if (is_string($goalError))
                        <p class="goal-alert" role="alert">{{ $errors->first($goalError) }}</p>
                    @endif
                    <div class="goal-desk-grid">
                        <div class="goal-causes">
                            @foreach (\App\Enums\DonationCause::publicGoalCauses() as $cause)
                                @php($causeProgress = $donationProgress->for($cause))
                                <div class="goal-meter" aria-label="Progress toward {{ $cause->label() }}">
                                    <div>
                                        <span>{{ $cause->label() }}</span>
                                        <strong>&#8358;{{ $causeProgress->formattedRaised() }} / &#8358;{{ $causeProgress->formattedGoal() }}</strong>
                                    </div>
                                    <div class="goal-track">
                                        <div class="goal-fill" @style(['width' => $causeProgress->percent.'%'])></div>
                                    </div>
                                    <p>{{ $causeProgress->percent }}% raised</p>
                                </div>
                            @endforeach
                            <div class="goal-meter">
                                <div>
                                    <span>Amount spent so far</span>
                                    <strong>&#8358;{{ $donationProgress->formattedSpent() }}</strong>
                                </div>
                                <p>Total in bank: &#8358;{{ $donationProgress->formattedBank() }}</p>
                            </div>
                        </div>
                        @if ($user->canOpenPortal(\App\Enums\PortalPermission::Donors))
                            <form method="POST" action="{{ route('portal.donation-goal.update') }}" class="goal-form" novalidate>
                                @csrf
                                @method('PUT')
                                @foreach (\App\Enums\DonationCause::publicGoalCauses() as $cause)
                                    <div class="goal-field">
                                        <label for="goal-{{ $cause->value }}">{{ $cause->label() }} (NGN)</label>
                                        <input id="goal-{{ $cause->value }}" name="goals[{{ $cause->value }}]" type="number" min="0" step="1" value="{{ old('goals.'.$cause->value, (int) $donationProgress->for($cause)->goal) }}" required>
                                    </div>
                                @endforeach
                                <div class="goal-field">
                                    <label for="spent_amount">Amount spent so far (NGN)</label>
                                    <input id="spent_amount" name="spent_amount" type="number" min="0" step="1" value="{{ old('spent_amount', (int) $donationProgress->spent) }}" required>
                                </div>
                                <button class="solid-btn" type="submit">Save goals</button>
                            </form>
                        @endif
                    </div>
                </div>
                @if ($user->canOpenPortal(\App\Enums\PortalPermission::Site))
                    <div class="surface goal-desk" data-stamp="Footer">
                        <div class="panel-title">
                            <div>
                                <h2>Footer pictures</h2>
                                <p>Replace a picture in the public footer. A blank field keeps the current picture. JPG, PNG, or WEBP, up to 5 MB.</p>
                            </div>
                        </div>
                        @if (session('gallery_status'))
                            <p class="goal-status" role="status">{{ session('gallery_status') }}</p>
                        @endif
                        @php($galleryError = collect($errors->keys())->first(fn (string $key): bool => str_starts_with($key, 'gallery_')))
                        @if (is_string($galleryError))
                            <p class="goal-alert" role="alert">{{ $errors->first($galleryError) }}</p>
                        @endif
                        <form method="POST" action="{{ route('portal.site.gallery') }}" class="goal-form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="footer-gallery">
                                @foreach ($siteProfile->gallery() as $index => $image)
                                    @php($slot = $index + 1)
                                    <div class="goal-field">
                                        <label for="gallery_{{ $slot }}">Footer picture {{ $slot }}</label>
                                        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}">
                                        <input id="gallery_{{ $slot }}" name="gallery_{{ $slot }}" type="file" accept="image/jpeg,image/png,image/webp">
                                    </div>
                                @endforeach
                            </div>
                            <button class="solid-btn" type="submit">Save footer pictures</button>
                        </form>
                    </div>
                @endif
                <div class="split">
                    <div class="split-stack">
                        <div class="surface" data-stamp="Tickets">
                            <div class="panel-title">
                                <div>
                                    <h2>House tickets</h2>
                                    <p>What the ledger is asking of the office today.</p>
                                </div>
                            </div>
                            <div class="tickets">
                                <div class="overview-empty">
                                    <div>
                                        <span>Quiet ledger</span>
                                        <strong>No cases on the desk yet.</strong>
                                        <span>Report Live and donations will land here.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="surface" data-stamp="Chute">
                            <div class="panel-title">
                                <div>
                                    <h2>Inbound chute</h2>
                                    <p>Letters waiting on the office.</p>
                                </div>
                            </div>
                            <div class="chute">
                                <div class="overview-empty">
                                    <div>
                                        <span>Empty chute</span>
                                        <strong>No letters waiting on the office.</strong>
                                        <span>Contact and volunteer notes will gather here.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="surface" data-stamp="House">
                        <div class="panel-title">
                            <div>
                                <h2>The house</h2>
                                <p>Stand For Humanity Foundation · Owerri</p>
                            </div>
                        </div>
                        <div class="status-row">
                            <div class="status-item"><span>City</span><strong>Owerri</strong></div>
                            <div class="status-item"><span>State</span><strong>Imo</strong></div>
                            <div class="status-item"><span>Staff</span><strong>{{ $staffCount }}</strong></div>
                            <div class="status-item"><span>Role</span><strong>{{ $user->role->label() }}</strong></div>
                        </div>
                        <div class="matrix">
                            <a class="cell" href="{{ route('donate') }}">
                                <div>
                                    <div class="tag">Protect</div>
                                    <h3>Child protection</h3>
                                    <p>Open the donation desk for rescue and care.</p>
                                </div>
                                <div class="go">Open wing</div>
                            </a>
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::Cases))
                            <a class="cell" href="{{ route('portal.cases.index') }}">
                                <div>
                                    <div class="tag">Rescue</div>
                                    <h3>Report Live</h3>
                                    <p>Open the case desk for Report Live filings.</p>
                                </div>
                                <div class="go">Open wing</div>
                            </a>
                            @endif
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::Contacts))
                            <a class="cell" href="{{ route('portal.contacts.index') }}">
                                <div>
                                    <div class="tag">Contact</div>
                                    <h3>Contact desk</h3>
                                    <p>Read assistance requests from the public contact page.</p>
                                </div>
                                <div class="go">Open wing</div>
                            </a>
                            @endif
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::Volunteers))
                            <a class="cell" href="{{ route('portal.volunteers.index') }}">
                                <div>
                                    <div class="tag">Volunteer</div>
                                    <h3>Volunteer house</h3>
                                    <p>{{ $volunteerCount }} {{ \Illuminate\Support\Str::plural('application', $volunteerCount) }} awaiting or vetted on the desk.</p>
                                </div>
                                <div class="go">Open desk</div>
                            </a>
                            @endif
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::News))
                            <a class="cell" href="{{ route('portal.news.index') }}">
                                <div>
                                    <div class="tag">Journal</div>
                                    <h3>Publish a note</h3>
                                    <p>Write for the foundation journal.</p>
                                </div>
                                <div class="go">Open news</div>
                            </a>
                            @endif
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::Members))
                                <a class="cell" href="{{ route('portal.members.index') }}">
                                    <div>
                                        <div class="tag">Roll</div>
                                        <h3>Members</h3>
                                        <p>{{ $memberCount }} {{ \Illuminate\Support\Str::plural('member', $memberCount) }} on the foundation roll.</p>
                                    </div>
                                    <div class="go">Open roll</div>
                                </a>
                            @endif
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::Team))
                                <a class="cell" href="{{ route('portal.team.index') }}">
                                    <div>
                                        <div class="tag">Team</div>
                                        <h3>Public team</h3>
                                        <p>{{ $teamCount }} {{ \Illuminate\Support\Str::plural('person', $teamCount) }} on the public team pages.</p>
                                    </div>
                                    <div class="go">Open team</div>
                                </a>
                            @endif
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::Gallery))
                                <a class="cell" href="{{ route('portal.gallery.index') }}">
                                    <div>
                                        <div class="tag">Gallery</div>
                                        <h3>Pictures and videos</h3>
                                        <p>{{ $galleryCount }} {{ \Illuminate\Support\Str::plural('item', $galleryCount) }} on the public gallery.</p>
                                    </div>
                                    <div class="go">Open gallery</div>
                                </a>
                            @endif
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::Testimonies))
                                <a class="cell" href="{{ route('portal.testimonies.index') }}">
                                    <div>
                                        <div class="tag">Voices</div>
                                        <h3>Testimonies</h3>
                                        <p>{{ $testimonyCount }} {{ \Illuminate\Support\Str::plural('testimony', $testimonyCount) }} waiting for review.</p>
                                    </div>
                                    <div class="go">Open review</div>
                                </a>
                            @endif
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::Events))
                            <a class="cell" href="{{ route('portal.events.index') }}">
                                <div>
                                    <div class="tag">Calendar</div>
                                    <h3>Schedule a gathering</h3>
                                    <p>{{ $eventCount }} {{ \Illuminate\Support\Str::plural('event', $eventCount) }} on the public events page.</p>
                                </div>
                                <div class="go">Open events</div>
                            </a>
                            @endif
                            @if ($user->canOpenPortal(\App\Enums\PortalPermission::Subscribers))
                            <a class="cell" href="{{ route('portal.subscribers.index') }}">
                                <div>
                                    <div class="tag">Subscriber</div>
                                    <h3>Newsletter list</h3>
                                    <p>{{ $subscriberCount }} {{ \Illuminate\Support\Str::plural('subscriber', $subscriberCount) }} on the programme notes book.</p>
                                </div>
                                <div class="go">Open list</div>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="{{ asset('js/portal/command-desk.js') }}"></script>
</body>
</html>
