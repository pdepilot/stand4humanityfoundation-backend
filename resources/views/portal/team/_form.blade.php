<div class="settings-field">
    <label for="name">Name</label>
    <input id="name" name="name" type="text" value="{{ old('name', $teamMember->name ?? '') }}" required maxlength="160">
</div>
<div class="settings-field">
    <label for="role">Position</label>
    <input id="role" name="role" type="text" value="{{ old('role', $teamMember->role ?? '') }}" required maxlength="160" placeholder="Programme lead">
</div>
<div class="settings-field">
    <label for="photo">Portrait</label>
    @isset($teamMember)
        <img src="{{ $teamMember->photoUrl() }}" alt="{{ $teamMember->name }}" width="96" height="96">
    @endisset
    <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" @required(! isset($teamMember))>
    <p class="photo-hint">@isset($teamMember) Leave this blank to keep the current portrait. @endisset Upload a JPG, PNG, or WEBP. Max 5 MB.</p>
</div>
<div class="settings-field">
    <label for="facebook_url">Facebook</label>
    <input id="facebook_url" name="facebook_url" type="url" value="{{ old('facebook_url', $teamMember->facebook_url ?? '') }}" placeholder="https://facebook.com/…">
</div>
<div class="settings-field">
    <label for="x_url">X</label>
    <input id="x_url" name="x_url" type="url" value="{{ old('x_url', $teamMember->x_url ?? '') }}" placeholder="https://x.com/…">
</div>
<div class="settings-field">
    <label for="instagram_url">Instagram</label>
    <input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url', $teamMember->instagram_url ?? '') }}" placeholder="https://instagram.com/…">
</div>
<div class="settings-field">
    <label for="youtube_url">YouTube</label>
    <input id="youtube_url" name="youtube_url" type="url" value="{{ old('youtube_url', $teamMember->youtube_url ?? '') }}" placeholder="https://youtube.com/…">
</div>
