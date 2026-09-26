<div class="desk-bell" data-desk-bell data-feed="{{ route('portal.notifications.index') }}" data-read-all="{{ route('portal.notifications.read-all') }}" data-read-one="{{ url('/portal/notifications') }}">
    <button class="desk-bell-button" type="button" aria-expanded="false" aria-controls="deskBellPanel" title="Desk alerts">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 8a6 6 0 1 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
            <path d="M10 21a2 2 0 0 0 4 0"></path>
        </svg>
        <span class="desk-bell-badge" data-bell-count hidden>0</span>
    </button>
    <p class="desk-bell-hint" data-bell-hint>Click or press a key once to arm the urgent alert sound.</p>
    <div class="desk-bell-panel" id="deskBellPanel" hidden>
        <div class="desk-bell-head">
            <strong>Desk alerts</strong>
            <button type="button" data-bell-clear>Mark all read</button>
        </div>
        <ul class="desk-bell-list" data-bell-list>
            <li class="desk-bell-empty">No alerts yet.</li>
        </ul>
    </div>
</div>
<script src="{{ asset('js/portal/notifications.js') }}" defer></script>
