@if ($errors->any())
    <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
@endif

@php
    $event = isset($foundationEvent) ? $foundationEvent : null;
    $highlightValue = old('highlights');
    if ($highlightValue === null && $event !== null) {
        $highlightValue = implode("\n", $event->highlightList());
    }
@endphp

<p class="news-form-hint">Published gatherings appear on the public events page. The featured gathering is the lead story at the top.</p>

<div class="settings-grid">
    <div class="settings-field">
        <label for="title">Title</label>
        <input id="title" name="title" type="text" value="{{ old('title', $event?->title) }}" required>
    </div>
    <div class="settings-field">
        <label for="slug">URL slug</label>
        <input id="slug" name="slug" type="text" value="{{ old('slug', $event?->slug) }}" placeholder="auto from title" required>
    </div>
</div>

<div class="settings-field">
    <label for="summary">Card summary</label>
    <textarea id="summary" name="summary" rows="3" required>{{ old('summary', $event?->summary) }}</textarea>
</div>

<div class="settings-field">
    <label for="description">Featured description</label>
    <textarea id="description" name="description" rows="5" required>{{ old('description', $event?->description) }}</textarea>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="image">Image</label>
        @if ($event)
            <div class="news-current-cover">
                <img src="{{ $event->imageUrl() }}" alt="{{ $event->image_alt }}">
                <span>{{ $event->hasUploadedImage() ? 'Current upload' : 'Programme library image' }}</span>
            </div>
        @endif
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif" @required(! $event)>
        <div class="news-count-line">
            <span>{{ $event ? 'Leave empty to keep the current image' : 'JPG, PNG, WEBP, or GIF · max 5 MB' }}</span>
        </div>
    </div>
    <div class="settings-field">
        <label for="image_alt">Image description</label>
        <input id="image_alt" name="image_alt" type="text" value="{{ old('image_alt', $event?->image_alt) }}" required>
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="location">Location</label>
        <input id="location" name="location" type="text" value="{{ old('location', $event?->location) }}" required>
    </div>
    <div class="settings-field">
        <label for="audience">Audience</label>
        <input id="audience" name="audience" type="text" value="{{ old('audience', $event?->audience) }}" placeholder="Open to the public">
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="starts_at">Starts</label>
        <input id="starts_at" name="starts_at" type="datetime-local" value="{{ old('starts_at', $event?->starts_at?->timezone(config('app.timezone'))->format('Y-m-d\TH:i')) }}" required>
    </div>
    <div class="settings-field">
        <label for="ends_at">Ends</label>
        <input id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at', $event?->ends_at?->timezone(config('app.timezone'))->format('Y-m-d\TH:i')) }}">
    </div>
</div>

<div class="settings-grid">
    <div class="settings-field">
        <label for="action">Title link</label>
        <select id="action" name="action" required>
            @foreach (\App\Enums\EventAction::cases() as $action)
                <option value="{{ $action->value }}" @selected(old('action', $event?->action?->value ?? 'contact') === $action->value)>{{ $action->label() }}</option>
            @endforeach
        </select>
    </div>
    <div class="settings-field">
        <label for="highlights">Featured highlights</label>
        <textarea id="highlights" name="highlights" rows="4" placeholder="One highlight per line">{{ $highlightValue }}</textarea>
    </div>
</div>

<div class="news-check-row">
    <label>
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $event?->is_published ?? true))>
        <span><strong>Published on the events page</strong><br>Drafts stay on the desk until you publish them.</span>
    </label>
    <label>
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $event?->is_featured ?? false))>
        <span><strong>Feature this gathering</strong><br>Only one gathering leads the public events page.</span>
    </label>
</div>
