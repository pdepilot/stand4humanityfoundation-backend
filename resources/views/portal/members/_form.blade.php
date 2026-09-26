@if ($errors->any())
    <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
@endif

@php
    /** @var \App\Models\FoundationMember|null $record */
    $record = isset($member) ? $member : null;
@endphp

<p class="news-form-hint">The desk keeps a portrait, contact details, and the member number used to find this person later.</p>

<div class="settings-field">
    <label for="full_name">Full name</label>
    <input id="full_name" name="full_name" type="text" value="{{ old('full_name', $record?->full_name) }}" required>
</div>

<div class="settings-field" data-member-photo>
    <label for="photo">Photo</label>
    <div class="photo-desk">
        <div class="photo-frame" data-photo-frame>
            @if ($record)
                <img data-photo-preview src="{{ $record->photoUrl() }}" alt="{{ $record->full_name }}">
            @else
                <img data-photo-preview alt="" hidden>
                <span class="photo-placeholder" data-photo-placeholder>Portrait</span>
            @endif
            <video data-photo-video playsinline autoplay muted hidden></video>
        </div>
        <div>
            <div class="photo-actions">
                <button class="ghost-btn" type="button" data-photo-upload>Upload photo</button>
                <button class="ghost-btn" type="button" data-photo-camera>Use camera</button>
                <button class="solid-btn" type="button" data-photo-capture hidden>Capture</button>
                <button class="ghost-btn" type="button" data-photo-cancel hidden>Close camera</button>
            </div>
            <p class="photo-hint" data-photo-status>{{ $record ? 'Upload a new photo or use the camera. Leave both empty to keep the current portrait.' : 'Upload a JPG, PNG, or WEBP, or take a photo with the camera. Max 5 MB.' }}</p>
            <input id="photo" class="member-photo-file" data-photo-input name="photo" type="file" accept="image/jpeg,image/png,image/webp" @required(! $record)>
        </div>
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="">Choose</option>
            @foreach ($genders as $gender)
                <option value="{{ $gender->value }}" @selected(old('gender', $record?->gender?->value) === $gender->value)>{{ $gender->label() }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="date_of_birth">Date of birth</label>
        <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', $record?->date_of_birth?->toDateString()) }}" required>
    </div>
    <div class="settings-field">
        <label for="phone">Phone</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $record?->phone) }}" required>
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $record?->email) }}">
    </div>
    <div class="settings-field">
        <label for="occupation">Occupation</label>
        <input id="occupation" name="occupation" type="text" value="{{ old('occupation', $record?->occupation) }}">
    </div>
</div>

<div class="settings-field">
    <label for="address">Home address</label>
    <input id="address" name="address" type="text" value="{{ old('address', $record?->address) }}" required>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="city">City</label>
        <input id="city" name="city" type="text" value="{{ old('city', $record?->city) }}" required>
    </div>
    <div class="settings-field">
        <label for="state">State</label>
        <select id="state" name="state" required>
            <option value="" disabled @selected(! old('state', $record?->state))>Select state</option>
            @foreach (\App\Support\NigerianState::names() as $state)
                <option value="{{ $state }}" @selected(old('state', $record?->state ?? 'Imo') === $state)>{{ $state }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="joined_on">Date joined</label>
        <input id="joined_on" name="joined_on" type="date" value="{{ old('joined_on', $record?->joined_on?->toDateString()) }}" required>
    </div>
    <div class="settings-field">
        <label for="status">Membership status</label>
        <select id="status" name="status" required>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(old('status', $record?->status?->value ?? 'active') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="emergency_contact_name">Emergency contact</label>
        <input id="emergency_contact_name" name="emergency_contact_name" type="text" value="{{ old('emergency_contact_name', $record?->emergency_contact_name) }}">
    </div>
    <div class="settings-field">
        <label for="emergency_contact_phone">Emergency phone</label>
        <input id="emergency_contact_phone" name="emergency_contact_phone" type="text" value="{{ old('emergency_contact_phone', $record?->emergency_contact_phone) }}">
    </div>
</div>

<div class="settings-field">
    <label for="notes">Notes</label>
    <textarea id="notes" name="notes" rows="4">{{ old('notes', $record?->notes) }}</textarea>
</div>

@once
    <script src="{{ asset('js/portal/member-photo.js') }}"></script>
@endonce
