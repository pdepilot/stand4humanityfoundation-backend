<aside class="rail" aria-label="Foundation command navigation">
    @if (auth()->user()?->isAdministrator())
        @include('portal.partials.notifications')
    @endif
    <div class="rail-mark">STAND</div>
    <nav class="rail-nav">
        <a class="rail-btn @if (($active ?? '') === 'desk') active @endif" @if (($active ?? '') === 'desk') aria-current="page" @endif href="{{ route('portal.dashboard') }}"><span>Desk</span></a>
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Donors))
            <a class="rail-btn @if (($active ?? '') === 'donors') active @endif" @if (($active ?? '') === 'donors') aria-current="page" @endif href="{{ route('portal.donors.index') }}"><span>Donors</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Cases))
            <a class="rail-btn @if (($active ?? '') === 'cases') active @endif" @if (($active ?? '') === 'cases') aria-current="page" @endif href="{{ route('portal.cases.index') }}"><span>Cases</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::News))
            <a class="rail-btn @if (($active ?? '') === 'news') active @endif" @if (($active ?? '') === 'news') aria-current="page" @endif href="{{ route('portal.news.index') }}"><span>News</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Subscribers))
            <a class="rail-btn @if (($active ?? '') === 'list') active @endif" @if (($active ?? '') === 'list') aria-current="page" @endif href="{{ route('portal.subscribers.index') }}"><span>Subscriber</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Events))
            <a class="rail-btn @if (($active ?? '') === 'events') active @endif" @if (($active ?? '') === 'events') aria-current="page" @endif href="{{ route('portal.events.index') }}"><span>Events</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Members))
            <a class="rail-btn @if (($active ?? '') === 'members') active @endif" @if (($active ?? '') === 'members') aria-current="page" @endif href="{{ route('portal.members.index') }}"><span>Members</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Team))
            <a class="rail-btn @if (($active ?? '') === 'team') active @endif" @if (($active ?? '') === 'team') aria-current="page" @endif href="{{ route('portal.team.index') }}"><span>Team</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Gallery))
            <a class="rail-btn @if (($active ?? '') === 'gallery') active @endif" @if (($active ?? '') === 'gallery') aria-current="page" @endif href="{{ route('portal.gallery.index') }}"><span>Gallery</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Testimonies))
            <a class="rail-btn @if (($active ?? '') === 'testimony') active @endif" @if (($active ?? '') === 'testimony') aria-current="page" @endif href="{{ route('portal.testimonies.index') }}"><span>Testimony</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Attendance))
            <a class="rail-btn @if (($active ?? '') === 'attendance') active @endif" @if (($active ?? '') === 'attendance') aria-current="page" @endif href="{{ route('portal.members.attendance') }}"><span>Attendance</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Volunteers))
            <a class="rail-btn @if (($active ?? '') === 'hands') active @endif" @if (($active ?? '') === 'hands') aria-current="page" @endif href="{{ route('portal.volunteers.index') }}"><span>Volunteer</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Contacts))
            <a class="rail-btn @if (($active ?? '') === 'reach') active @endif" @if (($active ?? '') === 'reach') aria-current="page" @endif href="{{ route('portal.contacts.index') }}"><span>Contact</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Mail))
            <a class="rail-btn @if (($active ?? '') === 'mail') active @endif" @if (($active ?? '') === 'mail') aria-current="page" @endif href="{{ route('portal.mail.index') }}"><span>Mail</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Audit))
            <a class="rail-btn @if (($active ?? '') === 'audit') active @endif" @if (($active ?? '') === 'audit') aria-current="page" @endif href="{{ route('portal.audit.index') }}"><span>Audit</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Bans))
            <a class="rail-btn @if (($active ?? '') === 'bans') active @endif" @if (($active ?? '') === 'bans') aria-current="page" @endif href="{{ route('portal.login-bans.index') }}"><span>Bans</span></a>
        @endif
        <a class="rail-btn" href="{{ route('home') }}"><span>Site</span></a>
        @if (auth()->user()?->isSuperAdministrator())
            <a class="rail-btn @if (($active ?? '') === 'admins') active @endif" @if (($active ?? '') === 'admins') aria-current="page" @endif href="{{ route('portal.admins.index') }}"><span>Admins</span></a>
            <a class="rail-btn @if (($active ?? '') === 'roles') active @endif" @if (($active ?? '') === 'roles') aria-current="page" @endif href="{{ route('portal.roles.index') }}"><span>Roles</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Site))
            <a class="rail-btn @if (($active ?? '') === 'site') active @endif" @if (($active ?? '') === 'site') aria-current="page" @endif href="{{ route('portal.site.edit') }}"><span>Site</span></a>
        @endif
        @if (auth()->user()?->canOpenPortal(\App\Enums\PortalPermission::Settings))
            <a class="rail-btn @if (($active ?? '') === 'settings') active @endif" @if (($active ?? '') === 'settings') aria-current="page" @endif href="{{ route('portal.settings.edit') }}"><span>Settings</span></a>
        @endif
    </nav>
    <form class="rail-exit-form" method="POST" action="{{ route('portal.logout') }}">
        @csrf
        <button class="rail-exit" type="submit" title="Log out">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M10 7V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2v-2"></path>
                <path d="M15 12H3"></path>
                <path d="M6 9l-3 3 3 3"></path>
            </svg>
            <strong>Log out</strong>
        </button>
    </form>
</aside>
