<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $item->title }} | Gallery</title>
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
<body data-page="gallery">
    <div class="overview-rule" aria-hidden="true"></div>
    <div class="vault">
        @include('portal.partials.rail', ['active' => 'gallery'])
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
                        <p class="eyebrow">Command · Gallery</p>
                        <h1 class="head-title">{{ $item->title }}</h1>
                        <p class="head-copy">{{ $item->kind->label() }}</p>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface news-preview-card" data-stamp="Gallery">
                        <div class="panel-title">
                            <div>
                                <h2>{{ $item->title }}</h2>
                                <p>Shown on the public gallery</p>
                            </div>
                            <a class="ghost-btn" href="{{ route('portal.gallery.index') }}">Back to the gallery</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        <div class="news-preview-shell">
                            @if ($item->isVideo())
                                <video class="news-preview-cover" controls playsinline preload="metadata">
                                    <source src="{{ $item->mediaUrl() }}" type="{{ $item->mimeType() }}">
                                </video>
                            @else
                                <img class="news-preview-cover" src="{{ $item->mediaUrl() }}" alt="{{ $item->title }}">
                            @endif
                            <div class="news-preview-body">
                                <h1>{{ $item->title }}</h1>
                                <p style="white-space: pre-wrap;">{{ $item->details }}</p>
                            </div>
                        </div>
                    </div>

                    <aside class="surface settings-card news-side-card" data-stamp="Desk">
                        <div class="panel-title">
                            <div>
                                <h2>Details</h2>
                                <p>Public gallery item</p>
                            </div>
                        </div>

                        <a class="ghost-btn" href="{{ route('portal.gallery.edit', $item) }}">Edit title and details</a>
                        <a class="ghost-btn" href="{{ route('gallery.show', $item) }}">View on the site</a>

                        <form method="POST" action="{{ route('portal.gallery.destroy', $item) }}" class="donors-destroy" onsubmit="return confirm('Remove this item from the gallery?');">
                            @csrf
                            @method('DELETE')
                            <button class="ghost-btn" type="submit">Remove from the gallery</button>
                        </form>
                    </aside>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
