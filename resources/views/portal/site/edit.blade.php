<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Public Site | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="robots" content="noindex, nofollow">
    <link href="{{ asset('images/portal/stand-logo.png') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/portal-command.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-settings.css') }}">
    <link rel="stylesheet" href="{{ asset('css/portal-donors.css') }}">
</head>
<body data-page="site">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'site'])
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
                        <p class="eyebrow">Command · Public site</p>
                        <h1 class="head-title">Site details</h1>
                        <p class="head-copy">Office line, social links, the yellow-bar video, response hours, and the footer gallery. A blank social link hides that icon. The video and sentence appear on every public page that shows the play button.</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface settings-card news-form-card" data-stamp="Site">
                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif
                        @if ($errors->any())
                            <div class="settings-alert" role="alert">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="{{ route('portal.site.update') }}" class="settings-form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="settings-field">
                                <label for="address">Office address</label>
                                <input id="address" name="address" type="text" value="{{ old('address', $profile->address) }}" required maxlength="160">
                            </div>
                            <div class="settings-field">
                                <label for="phone">Phone</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone', $profile->phone) }}" required maxlength="40">
                            </div>
                            <div class="settings-field">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $profile->email) }}" required maxlength="120">
                            </div>

                            <div class="settings-field">
                                <label for="impact_video_url">Yellow-bar video</label>
                                <input id="impact_video_url" name="impact_video_url" type="url" value="{{ old('impact_video_url', $profile->impact_video_url) }}" placeholder="https://www.youtube.com/watch?v=" maxlength="255">
                            </div>
                            <div class="settings-field">
                                <label for="impact_video_caption">Yellow-bar sentence</label>
                                <input id="impact_video_caption" name="impact_video_caption" type="text" value="{{ old('impact_video_caption', $profile->impact_video_caption ?: $profile->impactVideoCaption()) }}" maxlength="240">
                            </div>

                            <div class="settings-field">
                                <label for="x_url">X link</label>
                                <input id="x_url" name="x_url" type="url" value="{{ old('x_url', $profile->x_url) }}" placeholder="https://">
                            </div>
                            <div class="settings-field">
                                <label for="facebook_url">Facebook link</label>
                                <input id="facebook_url" name="facebook_url" type="url" value="{{ old('facebook_url', $profile->facebook_url) }}" placeholder="https://">
                            </div>
                            <div class="settings-field">
                                <label for="instagram_url">Instagram link</label>
                                <input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url', $profile->instagram_url) }}" placeholder="https://">
                            </div>
                            <div class="settings-field">
                                <label for="youtube_url">YouTube link</label>
                                <input id="youtube_url" name="youtube_url" type="url" value="{{ old('youtube_url', $profile->youtube_url) }}" placeholder="https://">
                            </div>
                            <div class="settings-field">
                                <label for="linkedin_url">LinkedIn link</label>
                                <input id="linkedin_url" name="linkedin_url" type="url" value="{{ old('linkedin_url', $profile->linkedin_url) }}" placeholder="https://">
                            </div>

                            <div class="settings-field">
                                <label for="weekday_label">Weekday heading</label>
                                <input id="weekday_label" name="weekday_label" type="text" value="{{ old('weekday_label', $profile->weekday_label) }}" required maxlength="80">
                            </div>
                            <div class="settings-field">
                                <label for="weekday_hours">Weekday hours</label>
                                <input id="weekday_hours" name="weekday_hours" type="text" value="{{ old('weekday_hours', $profile->weekday_hours) }}" required maxlength="80">
                            </div>
                            <div class="settings-field">
                                <label for="saturday_label">Saturday heading</label>
                                <input id="saturday_label" name="saturday_label" type="text" value="{{ old('saturday_label', $profile->saturday_label) }}" required maxlength="80">
                            </div>
                            <div class="settings-field">
                                <label for="saturday_hours">Saturday hours</label>
                                <input id="saturday_hours" name="saturday_hours" type="text" value="{{ old('saturday_hours', $profile->saturday_hours) }}" required maxlength="80">
                            </div>
                            <div class="settings-field">
                                <label for="extra_label">Third heading</label>
                                <input id="extra_label" name="extra_label" type="text" value="{{ old('extra_label', $profile->extra_label) }}" required maxlength="80">
                            </div>
                            <div class="settings-field">
                                <label for="extra_hours">Third hours</label>
                                <input id="extra_hours" name="extra_hours" type="text" value="{{ old('extra_hours', $profile->extra_hours) }}" required maxlength="80">
                            </div>

                            @foreach (range(1, 6) as $slot)
                                <div class="settings-field">
                                    <label for="gallery_{{ $slot }}">Footer picture {{ $slot }}</label>
                                    <img src="{{ $profile->gallery()[$slot - 1]['url'] }}" alt="" width="72" height="72">
                                    <input id="gallery_{{ $slot }}" name="gallery_{{ $slot }}" type="file" accept="image/jpeg,image/png,image/webp">
                                </div>
                            @endforeach

                            <button class="settings-submit" type="submit">Save site details</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
