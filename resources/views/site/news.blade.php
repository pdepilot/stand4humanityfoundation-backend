<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>News | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Stand For Humanity Foundation news, child protection, victim support, humanitarian assistance, Owerri, Imo State, Nigeria" name="keywords">
    <meta content="Programme notes from Stand For Humanity Foundation in Owerri, Imo State — child protection, victim support, rescue, and community awareness." name="description">
    <meta property="og:title" content="News | Stand For Humanity Foundation">
    <meta property="og:description" content="Read programme notes from Stand For Humanity Foundation in Owerri, Imo State, Nigeria.">
    <meta property="og:type" content="website">
    <script type="application/ld+json">
    {"@@context":"https://schema.org","@@type":"NewsMediaOrganization","name":"Stand For Humanity Foundation News","url":"https://www.standforhumanityfoundation.org/news","parentOrganization":{"@@type":"NGO","name":"Stand For Humanity Foundation"}}
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
                                <a href="{{ route('gallery') }}" class="dropdown-item">
                                    <strong>Gallery</strong>
                                    <span>Photographs and videos from our work</span>
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
            <h1 class="display-3 animated slideInDown">Programme Notes</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">News</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto blog-intro mb-5 wow fadeIn" data-wow-delay="0.1s">
                <p class="blog-kicker mb-2">Stand For Humanity Foundation Journal</p>
                <h2 class="display-6 mb-3">Original programme notes from Owerri, Imo State</h2>
                <p class="mb-4">We publish original writing about child protection, victim support, rescue, and community awareness. We withhold names of minors and survivors. These pages are for readers first — if you need help now, use Report Live or Contact.</p>
                <form class="blog-search-form" data-news-search role="search">
                    <input type="search" name="q" value="{{ $searchQuery }}" placeholder="Search programme notes..." aria-label="Search programme notes" autocomplete="off">
                    <button type="submit" aria-label="Search"><i class="fa fa-search"></i></button>
                </form>
                <div class="blog-search-hints" aria-label="Search suggestions">
                    <button type="button" data-news-hint="child protection">Child protection</button>
                    <button type="button" data-news-hint="domestic abuse">Domestic abuse</button>
                    <button type="button" data-news-hint="volunteers">Volunteers</button>
                    <button type="button" data-news-hint="report">Report a case</button>
                </div>
            </div>

            <div class="row g-4 g-xl-5">
                <div class="col-lg-8">
                    @php
                        $featured = $articles->first();
                        $rest = $articles->slice(1)->values();
                    @endphp

                    @if ($featured)
                        <article class="news-feature wow fadeInUp" data-wow-delay="0.1s" data-news-item data-search="{{ $featured->searchHaystack() }}">
                            <a class="news-feature-media" href="{{ route('news.show', $featured->slug) }}">
                                <img src="{{ $featured->imageUrl() }}" alt="{{ $featured->image_alt }}" loading="eager">
                            </a>
                            <div class="news-feature-body">
                                <p class="blog-card-meta">{{ $featured->metaLine() }}</p>
                                <h2 class="news-feature-title"><a href="{{ route('news.show', $featured->slug) }}">{{ $featured->title }}</a></h2>
                                <p class="news-feature-excerpt">{{ $featured->excerpt }}</p>
                                <a class="btn btn-secondary py-2 px-4" href="{{ route('news.show', $featured->slug) }}">Continue reading</a>
                            </div>
                        </article>
                    @endif

                    <div class="news-list">
                        @forelse ($rest as $index => $article)
                            <article class="news-list-item wow fadeInUp" data-wow-delay="{{ number_format(0.15 + ($index * 0.05), 2) }}s" data-news-item data-search="{{ $article->searchHaystack() }}">
                                <a class="news-list-media" href="{{ route('news.show', $article->slug) }}">
                                    <img src="{{ $article->imageUrl() }}" alt="{{ $article->image_alt }}" loading="lazy">
                                </a>
                                <div class="news-list-copy">
                                    <p class="blog-card-meta">{{ $article->metaLine() }}</p>
                                    <h3><a href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a></h3>
                                    <p>{{ $article->excerpt }}</p>
                                </div>
                            </article>
                        @empty
                            @unless ($featured)
                                <p class="text-center mb-0">No programme notes are published yet.</p>
                            @endunless
                        @endforelse
                    </div>
                    <p class="blog-empty" data-news-empty>No matching notes. Try another word, or clear the search to see every programme note.</p>
                </div>

                <aside class="col-lg-4">
                    <div class="blog-side-stack sticky-panel">
                        <div class="surface-panel wow fadeIn" data-wow-delay="0.2s">
                            <h2 class="blog-side-title">About this journal</h2>
                            <div class="news-publisher">
                                <strong>Stand For Humanity Foundation</strong>
                                <p class="mb-0">A humanitarian organisation in Owerri, Imo State, Nigeria. We protect children, support victims of abuse, and help people in need. Programme photos never identify minors or survivors.</p>
                                <div class="news-legal-links">
                                    <a href="{{ route('about') }}">About</a>
                                    <a href="{{ route('contact') }}">Contact</a>
                                    <a href="{{ route('privacy') }}">Privacy</a>
                                    <a href="{{ route('terms') }}">Terms</a>
                                </div>
                            </div>
                        </div>
                        <div class="surface-panel wow fadeIn" data-wow-delay="0.25s">
                            <h2 class="blog-side-title">Popular content</h2>
                            <ul class="blog-mini-list">
                                @forelse ($popular as $item)
                                    <li>
                                        <a href="{{ route('news.show', $item->slug) }}">{{ $item->title }}</a>
                                        <span>{{ $item->reading_minutes }} min read</span>
                                    </li>
                                @empty
                                    <li><span>Popular notes will appear here.</span></li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="surface-panel wow fadeIn" data-wow-delay="0.3s">
                            <h2 class="blog-side-title">Need help now?</h2>
                            <p>These notes are not a case file. If someone is missing, abused, or in danger, report it directly.</p>
                            <a class="btn btn-secondary w-100 py-3 mb-2" href="{{ route('report') }}">Report Live</a>
                            <a class="btn btn-primary w-100 py-3" href="{{ route('contact') }}">Contact the desk</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <div class="container-fluid banner py-5">
        <div class="container">
            <div class="banner-inner bg-light p-5 wow fadeIn" data-wow-delay="0.1s">
                <div class="row justify-content-center">
                    <div class="col-lg-8 py-5 text-center">
                        <h2 class="display-6 wow fadeIn" data-wow-delay="0.3s">Support the work behind these notes</h2>
                        <p class="fs-5 mb-4 wow fadeIn" data-wow-delay="0.5s">Child protection, victim support, and humanitarian help are made possible by people who decided the next family should not wait alone.</p>
                        <div class="d-flex justify-content-center flex-wrap gap-2 wow fadeIn" data-wow-delay="0.7s">
                            <a class="btn btn-primary py-3 px-4" href="{{ route('donate') }}">Donate</a>
                            <a class="btn btn-secondary py-3 px-4" href="{{ route('volunteer') }}">Volunteer</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('site.partials.newsletter', ['heading' => 'Get programme notes', 'copy' => 'Programme notes only. No spam, no unverified alerts.'])

@include('site.partials.footer')

    <a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top" aria-label="Back to top"><i class="bi bi-arrow-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('Charitize/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('Charitize/js/main.js') }}?v=impact-video"></script>
    <script src="{{ asset('Charitize/js/cookie-banner.js') }}" defer></script>
    <div id="news-article-routes" hidden data-routes="{{ \Illuminate\Support\Js::from($articles->mapWithKeys(fn ($article) => [$article->slug => route('news.show', $article->slug)])) }}"></div>
    <script src="{{ asset('Charitize/js/news.js') }}?v=journal-routes"></script>
</body>

</html>
