<form method="POST" action="{{ $action }}" class="settings-form" enctype="multipart/form-data">
    @csrf
    @isset($method)
        @method($method)
    @endisset

    <div class="settings-field">
        <label for="title">Title</label>
        <input id="title" name="title" type="text" value="{{ old('title', $item->title ?? '') }}" required maxlength="160">
    </div>
    <div class="settings-field">
        <label for="details">Details</label>
        <textarea id="details" name="details" required maxlength="2000" rows="6">{{ old('details', $item->details ?? '') }}</textarea>
    </div>
    <div class="settings-field">
        <label for="kind">Type</label>
        <select id="kind" name="kind" required>
            <option value="image" @selected(old('kind', isset($item) ? $item->kind->value : 'image') === 'image')>Picture</option>
            <option value="video" @selected(old('kind', isset($item) ? $item->kind->value : 'image') === 'video')>Video</option>
        </select>
    </div>
    <div class="settings-field">
        <label for="media">{{ isset($item) ? 'Replacement file' : 'File' }}</label>
        <input id="media" name="media" type="file" accept="image/jpeg,image/png,image/webp,video/mp4,video/webm" @unless(isset($item)) required @endunless>
        <p class="photo-hint">Pictures: JPG, PNG, or WEBP, up to 5 MB. Videos: MP4 or WEBM, up to 15 MB. Visitors watch the video on the gallery page.</p>
    </div>
    <button class="settings-submit" type="submit">{{ isset($item) ? 'Save gallery item' : 'Add to the gallery' }}</button>
</form>
