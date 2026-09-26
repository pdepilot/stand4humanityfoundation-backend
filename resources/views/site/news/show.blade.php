<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $article->title }} | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Stand For Humanity Foundation, {{ implode(', ', $article->tagList()) }}, Owerri, Imo State, Nigeria" name="keywords">
    <meta content="{{ $article->excerpt }}" name="description">
    <meta property="og:title" content="{{ $article->title }} | Stand For Humanity Foundation">
    <meta property="og:description" content="{{ $article->excerpt }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $article->title,
        'description' => $article->excerpt,
        'image' => $article->imageUrl(),
        'datePublished' => optional($article->published_at)->toAtomString(),
        'dateModified' => optional($article->updated_at)->toAtomString(),
        'author' => [
            '@type' => 'Organization',
            'name' => $article->author,
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Stand For Humanity Foundation',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('Charitize/img/stand-logo.png'),
            ],
        ],
        'mainEntityOfPage' => url()->current(),
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
    </script>

    <link href="{{ asset('Charitize/img/favicon.ico') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('Charitize/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Charitize/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Charitize/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Charitize/css/style.css') }}?v=nav-more" rel="stylesheet">
    <link href="{{ asset('Charitize/css/cookie-banner.css') }}" rel="stylesheet">
    @include('site.partials.adsense')
    <link href="{{ asset('Charitize/css/news.css') }}?v=adsense" rel="stylesheet">
</head>

<body class="news-page">
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>

    <div class="container-fluid bg-secondary top-bar wow fadeIn" data-wow-delay="0.1s">
        <div class="row align-items-center h-100">
            <div class="col-12 col-lg-auto text-center text-lg-start pe-lg-4">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('Charitize/img/stand-logo.png') }}?v=blue" alt="Stand For Humanity Foundation" class="m-0" style="height: 80px;">
                </a>
            </div>
            <div class="col d-none d-lg-block">
                @include('site.partials.top-contacts')
            </div>
        </div>
    </div>

    <div class="container-fluid bg-secondary px-0">
        <div class="nav-bar">
            <nav class="navbar navbar-expand-lg bg-primary navbar-dark px-4 py-lg-0">
                <h4 class="d-lg-none m-0">Menu</h4>
                <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav me-auto">
                        <a href="{{ route('home') }}" class="nav-item nav-link">Home</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link">About</a>
                        <a href="{{ route('services') }}" class="nav-item nav-link">Services</a>
                        <a href="{{ route('donations') }}" class="nav-item nav-link">Donations</a>
                        <a href="{{ route('news') }}" class="nav-item nav-link active">News</a>
                        <div class="nav-item dropdown">
                            <a href="#!" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">More</a>
                            <div class="dropdown-menu more-menu m-0">
                                <a href="{{ route('events') }}" class="dropdown-item">
                                    <strong>Events</strong>
                                    <span>Awareness and community gatherings</span>
                                </a>
                                <a href="{{ route('mission') }}" class="dropdown-item">
                                    <strong>Our Mission</strong>
                                    <span>Protecting the vulnerable and the voiceless</span>
                                </a>
                                <a href="{{ route('volunteer') }}" class="dropdown-item">
                                    <strong>Volunteer</strong>
                                    <span>Join our volunteer and advocacy teams</span>
                                </a>
                                <a href="{{ route('our-stand') }}" class="dropdown-item">
                                    <strong>Our Stand</strong>
                                    <span>Standing with those who cannot stand alone</span>
                                </a>
                                <a href="{{ route('report') }}" class="dropdown-item">
                                    <strong>Report Live</strong>
                                    <span>File an urgent case now</span>
                                </a>
                                <a href="{{ route('help') }}" class="dropdown-item">
                                    <strong>Get Help</strong>
                                    <span>Reach the desk when you need us</span>
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('contact') }}" class="nav-item nav-link">Contact</a>
                        <a class="btn btn-donate-nav d-lg-none" href="{{ route('donate') }}" aria-label="Donate to support our mission">
                            <i class="fa fa-heart donate-heart" aria-hidden="true"></i>
                            Donate
                        </a>
                        <a class="btn btn-report-live d-lg-none" href="{{ route('report') }}" aria-label="Report Live - submit an urgent case">
                            <span class="live-dot" aria-hidden="true"></span>
                            Report Live
                        </a>
                    </div>
                    <div class="d-none d-lg-flex ms-auto align-items-center">
                        <a class="btn btn-donate-nav me-2" href="{{ route('donate') }}" aria-label="Donate to support our mission">
                            <i class="fa fa-heart donate-heart" aria-hidden="true"></i>
                            Donate
                        </a>
                        <a class="btn btn-report-live me-2" href="{{ route('report') }}" aria-label="Report Live - submit an urgent case">
                            <span class="live-dot" aria-hidden="true"></span>
                            Report Live
                        </a>
                        @include('site.partials.header-social')
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-4">
            <h1 class="display-3 animated slideInDown">{{ $article->headerLabel() }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('news') }}">News</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $article->headerLabel() }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4 g-xl-5">
                <aside class="col-lg-4 col-xl-3 order-2 order-lg-1">
                    <div class="blog-side-stack sticky-panel">
                        <div class="surface-panel wow fadeIn" data-wow-delay="0.15s">
                            <h2 class="blog-side-title">About the publisher</h2>
                            <div class="news-publisher">
                                <strong>Stand For Humanity Foundation</strong>
                                <p>Humanitarian work in Owerri, Imo State: child protection, victim support, and help for people in need.</p>
                                <div class="news-legal-links">
                                    <a href="{{ route('about') }}">About</a>
                                    <a href="{{ route('contact') }}">Contact</a>
                                    <a href="{{ route('privacy') }}">Privacy</a>
                                    <a href="{{ route('terms') }}">Terms</a>
                                </div>
                            </div>
                        </div>
                        <div class="surface-panel wow fadeIn" data-wow-delay="0.2s">
                            <h2 class="blog-side-title">Popular reads</h2>
                            <ul class="blog-mini-list">
                                @forelse ($popular as $item)
                                    <li>
                                        <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                                        <span>{{ $item->reading_minutes }} min read</span>
                                    </li>
                                @empty
                                    <li><span>More notes will appear here.</span></li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="surface-panel wow fadeIn" data-wow-delay="0.25s">
                            <h2 class="blog-side-title">Need help now?</h2>
                            <p>If someone is missing, abused, or in danger, report it directly.</p>
                            <a class="btn btn-secondary w-100 py-3 mb-2" href="{{ route('report') }}">Report Live</a>
                            <a class="btn btn-primary w-100 py-3" href="{{ route('contact') }}">Contact the desk</a>
                        </div>
                    </div>
                </aside>
                <div class="col-lg-8 col-xl-7 order-1 order-lg-2">
                    <article class="blog-article wow fadeInUp" data-wow-delay="0.1s">
                        <img class="blog-article-cover mb-4" src="{{ $article->imageUrl() }}" alt="{{ $article->image_alt }}" loading="eager">
                        <h1 class="blog-article-title">{{ $article->title }}</h1>
                        <div class="news-byline">
                            <span><strong>{{ $article->author }}</strong></span>
                            <span>{{ $article->published_at?->timezone(config('app.timezone'))->format('d F Y') }}</span>
                            <span>{{ $article->reading_minutes }} min read</span>
                            <span>{{ $article->wordCount() }} words</span>
                        </div>
                        @if ($article->tagList() !== [])
                            <div class="blog-tags mb-4">
                                @foreach ($article->tagList() as $tag)
                                    <a href="{{ route('news', ['q' => $tag]) }}">{{ $tag }}</a>
                                @endforeach
                            </div>
                        @endif
                        <div class="blog-article-body">
                            {!! $article->renderedBody() !!}
                        </div>
                        <div class="news-author-box">
                            <h2>About Stand For Humanity Foundation</h2>
                            <p>We stand with those who cannot stand alone. This journal explains our programmes. It is not a case file. Photographs show public programme activity, not victims of crime.</p>
                        </div>
                        @if ($popular->isNotEmpty())
                            <div class="news-related">
                                <h2>Continue reading</h2>
                                <ul class="blog-mini-list">
                                    @foreach ($popular->take(3) as $item)
                                        <li>
                                            <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                                            <span>{{ $item->reading_minutes }} min read</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </article>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid banner py-5">
        <div class="container">
            <div class="banner-inner bg-light p-5 wow fadeIn" data-wow-delay="0.1s">
                <div class="row justify-content-center">
                    <div class="col-lg-8 py-5 text-center">
                        <h2 class="display-6 wow fadeIn" data-wow-delay="0.3s">Need help, or want to help?</h2>
                        <p class="fs-5 mb-4 wow fadeIn" data-wow-delay="0.5s">If this note is about someone in danger, report it. If you can give time or means, stand with the next family.</p>
                        <div class="d-flex justify-content-center flex-wrap gap-2 wow fadeIn" data-wow-delay="0.7s">
                            <a class="btn btn-secondary py-3 px-4" href="{{ route('report') }}">Report Live</a>
                            <a class="btn btn-primary py-3 px-4" href="{{ route('donate') }}">Donate</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('site.partials.footer')

    <a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top" aria-label="Back to top"><i class="bi bi-arrow-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('Charitize/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('Charitize/js/main.js') }}?v=nav-more"></script>
    <script src="{{ asset('Charitize/js/cookie-banner.js') }}" defer></script>
</body>

</html>
