<div class="settings-field">
    <label for="name">Name</label>
    <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="160">
</div>
<div class="settings-field">
    <label for="role">Role</label>
    <input id="role" name="role" type="text" value="{{ old('role') }}" required maxlength="160" placeholder="Programme lead">
</div>
<div class="settings-field">
    <label for="photo">Portrait</label>
    <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" required>
    <p class="photo-hint">Upload a JPG, PNG, or WEBP. Max 5 MB.</p>
</div>
<div class="settings-field">
    <label for="facebook_url">Facebook</label>
    <input id="facebook_url" name="facebook_url" type="url" value="{{ old('facebook_url') }}" placeholder="https://facebook.com/…">
</div>
<div class="settings-field">
    <label for="x_url">X</label>
    <input id="x_url" name="x_url" type="url" value="{{ old('x_url') }}" placeholder="https://x.com/…">
</div>
<div class="settings-field">
    <label for="instagram_url">Instagram</label>
    <input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url') }}" placeholder="https://instagram.com/…">
</div>
<div class="settings-field">
    <label for="youtube_url">YouTube</label>
    <input id="youtube_url" name="youtube_url" type="url" value="{{ old('youtube_url') }}" placeholder="https://youtube.com/…">
</div>
