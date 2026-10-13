<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Gallery | Stand For Humanity Foundation</title>
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
                        <h1 class="head-title">Gallery</h1>
                        <p class="head-copy">Pictures and videos added here appear on the public gallery, each with its title and details.</p>
                    </div>
                    <div class="settings-seal">
                        <img src="{{ asset('images/portal/stand-logo.png') }}" alt="Stand For Humanity Foundation">
                        <div>
                            <span>On the site</span>
                            <strong>{{ $items->count() }} {{ \Illuminate\Support\Str::plural('item', $items->count()) }}</strong>
                        </div>
                    </div>
                </div>
            </header>
            <main class="workspace">
                <div class="news-desk-grid">
                    <div class="surface donors-card news-ledger-card" data-stamp="Gallery">
                        <div class="panel-title">
                            <div>
                                <h2>Public gallery</h2>
                                <p>Add a picture or a video, with a title and details</p>
                            </div>
                            <a class="solid-btn" href="{{ route('portal.gallery.create') }}">Add a gallery item</a>
                        </div>

                        @if (session('status'))
                            <p class="settings-status" role="status">{{ session('status') }}</p>
                        @endif

                        @if ($items->isEmpty())
                            <div class="overview-empty">
                                <div>
                                    <span>Quiet gallery</span>
                                    <strong>The gallery is empty.</strong>
                                    <span>Add a picture or a video, and write the title and details.</span>
                                </div>
                            </div>
                        @else
                            <div class="news-ledger">
                                @foreach ($items as $item)
                                    <div class="news-ledger-row">
                                        <a class="news-ledger-copy" href="{{ route('portal.gallery.show', $item) }}">
                                            <strong>{{ $item->title }}</strong>
                                            <span>{{ $item->kind->label() }}</span>
                                        </a>
                                        <form method="POST" action="{{ route('portal.gallery.destroy', $item) }}" onsubmit="return confirm('Remove this item from the gallery?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="ghost-btn" type="submit">Remove</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
