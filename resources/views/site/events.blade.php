<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Events | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Stand For Humanity Foundation, events, community awareness, volunteer orientation, child protection, Owerri, Imo State, Nigeria" name="keywords">
    <meta content="Join upcoming Stand For Humanity Foundation gatherings — community awareness, volunteer orientation, and family support in Owerri, Imo State." name="description">
    <meta property="og:title" content="Events | Stand For Humanity Foundation">
    <meta property="og:description" content="Explore community awareness, volunteer orientation, and outreach gatherings with Stand For Humanity Foundation in Owerri, Imo State.">

    <!-- Favicon -->
    <link href="{{ asset('Charitize/img/favicon.ico') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('Charitize/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Charitize/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('Charitize/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('Charitize/css/style.css') }}?v=nav-more" rel="stylesheet">
    <link href="{{ asset('Charitize/css/cookie-banner.css') }}" rel="stylesheet">
    @include('site.partials.adsense')
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
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
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-secondary px-0">
        <div class="nav-bar">
            <nav class="navbar navbar-expand-lg bg-primary navbar-dark px-4 py-lg-0">
                <h4 class="d-lg-none m-0">Menu</h4>
                <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav me-auto">
                        <a href="{{ route('home') }}" class="nav-item nav-link">Home</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link">About</a>
                        <a href="{{ route('services') }}" class="nav-item nav-link">Services</a>
                        <a href="{{ route('donations') }}" class="nav-item nav-link">Donations</a>
                        <a href="{{ route('news') }}" class="nav-item nav-link">News</a>
                        <div class="nav-item dropdown">
                            <a href="#!" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">More</a>
                            <div class="dropdown-menu more-menu m-0">
                                <a href="{{ route('events') }}" class="dropdown-item active">
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
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-4">
            <h1 class="display-3 animated slideInDown">Events</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#!">More</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Events</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Video Start -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-0">
                <div class="col-lg-11">
                    <div class="h-100 py-5 d-flex align-items-center">
                        @include('site.partials.impact-video-button')
                        <h3 class="ms-5 mb-0">{{ $siteProfile->impactVideoCaption() }}</h3>
                    </div>
                </div>
                <div class="d-none d-lg-block col-lg-1">
                    <div class="h-100 w-100 bg-secondary d-flex align-items-center justify-content-center">
                        <span class="text-white" style="transform: rotate(-90deg);">Scroll Down</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Video End -->


    <!-- Video Modal Start -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Youtube Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="ratio ratio-16x9">
                        <iframe class="embed-responsive-item" src="" id="video" allowfullscreen
                            allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Video Modal End -->


    <!-- Featured Event Start -->
    <div class="container-fluid py-5">
        <div class="container">
            @if ($featured)
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.2s">
                        <div class="about-img position-relative">
                            <img class="img-fluid w-100" src="{{ $featured->imageUrl() }}" alt="{{ $featured->image_alt }}">
                            <div class="position-absolute top-0 start-0 bg-secondary text-white text-center p-3 m-3">
                                <h2 class="display-6 text-primary mb-0">{{ $featured->dayNumber() }}</h2>
                                <span class="text-uppercase">{{ $featured->monthLabel() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="section-title bg-white text-start text-primary pe-3">Featured Event</p>
                        <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.2s">{{ $featured->title }}</h1>
                        <p class="mb-4 wow fadeIn" data-wow-delay="0.3s">{{ $featured->description }}</p>
                        <div class="bg-light p-4 mb-4 wow fadeIn" data-wow-delay="0.4s">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <p class="mb-2"><i class="fa fa-clock text-primary me-2"></i>{{ $featured->timeRange() }}</p>
                                    <p class="mb-0"><i class="fa fa-calendar-alt text-primary me-2"></i>{{ $featured->featuredDate() }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-2"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $featured->location }}</p>
                                    @if (filled($featured->audience))
                                        <p class="mb-0"><i class="fa fa-users text-primary me-2"></i>{{ $featured->audience }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @foreach ($featured->highlightList() as $highlight)
                            <p class="text-dark wow fadeIn" data-wow-delay="0.5s"><i class="fa fa-check text-primary me-2"></i>{{ $highlight }}</p>
                        @endforeach
                        <div class="d-flex mt-4 wow fadeIn" data-wow-delay="0.7s">
                            <a class="btn btn-primary py-3 px-4 me-3" href="{{ route('contact') }}">Register Interest</a>
                            <a class="btn btn-secondary py-3 px-4" href="{{ route('volunteer') }}">Volunteer Here</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center mx-auto" style="max-width: 560px;">
                    <p class="section-title bg-white text-center text-primary px-3">Featured Event</p>
                    <h1 class="display-6 mb-4">The next gathering is being prepared</h1>
                    <p class="mb-4">No public events are scheduled yet. Contact the desk to ask about the next community programme.</p>
                    <a class="btn btn-primary py-3 px-4" href="{{ route('contact') }}">Ask About Events</a>
                </div>
            @endif
        </div>
    </div>
    <!-- Featured Event End -->


    <!-- Upcoming Events Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 560px;">
                <p class="section-title bg-white text-center text-primary px-3">Upcoming</p>
                <h1 class="display-6 mb-4">Moments That Move Communities</h1>
                <p class="mb-0">From community awareness to volunteer orientation — every gathering is a chance to protect the vulnerable and build safer neighbourhoods.</p>
            </div>
            <div class="row g-4 mt-2">
                @forelse ($upcoming as $event)
                    <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                        <div class="event-item h-100 p-4">
                            <img class="img-fluid w-100 mb-4" src="{{ $event->imageUrl() }}" alt="{{ $event->image_alt }}" loading="lazy">
                            <a href="{{ $event->actionUrl() }}" class="h3 d-inline-block">{{ $event->title }}</a>
                            <p>{{ $event->summary }}</p>
                            <div class="bg-light p-4">
                                <p class="mb-1"><i class="fa fa-clock text-primary me-2"></i>{{ $event->timeRange() }}</p>
                                <p class="mb-1"><i class="fa fa-calendar-alt text-primary me-2"></i>{{ $event->cardDate() }}</p>
                                <p class="mb-0"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $event->location }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="mb-0">Upcoming gatherings will appear here once they are published.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Upcoming Events End -->


    <!-- How to Join Start -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    <p class="section-title bg-light text-start text-primary pe-3">Get Involved</p>
                    <h1 class="display-6 mb-4">Three Ways to Be Part of Every Event</h1>
                    <p class="mb-4">You do not need a title to matter here. Whether you donate, volunteer, or simply show up — your presence strengthens the cause.</p>
                    <a class="btn btn-primary py-3 px-4" href="{{ route('contact') }}">Ask About Events</a>
                </div>
                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-md-4 wow fadeIn" data-wow-delay="0.2s">
                            <div class="bg-white h-100 p-4 text-center">
                                <div class="btn-square bg-primary mx-auto mb-3">
                                    <i class="fa fa-hand-holding-heart fa-2x text-dark"></i>
                                </div>
                                <h4>Sponsor</h4>
                                <p class="mb-0">Fund logistics, refreshments, information desks, and outreach materials for community programs.</p>
                            </div>
                        </div>
                        <div class="col-md-4 wow fadeIn" data-wow-delay="0.4s">
                            <div class="bg-white h-100 p-4 text-center">
                                <div class="btn-square bg-primary mx-auto mb-3">
                                    <i class="fa fa-people-group fa-2x text-dark"></i>
                                </div>
                                <h4>Volunteer</h4>
                                <p class="mb-0">Join ushers, safety marshals, registration teams, and awareness ambassadors on event day.</p>
                            </div>
                        </div>
                        <div class="col-md-4 wow fadeIn" data-wow-delay="0.6s">
                            <div class="bg-white h-100 p-4 text-center">
                                <div class="btn-square bg-primary mx-auto mb-3">
                                    <i class="fa fa-bullhorn fa-2x text-dark"></i>
                                </div>
                                <h4>Amplify</h4>
                                <p class="mb-0">Share invitations, invite neighbours, and help more families discover support when they need it.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- How to Join End -->


    <!-- Banner Start -->
    <div class="container-fluid banner py-5">
        <div class="container">
            <div class="banner-inner bg-light p-5 wow fadeIn" data-wow-delay="0.1s">
                <div class="row justify-content-center">
                    <div class="col-lg-8 py-5 text-center">
                        <h1 class="display-6 wow fadeIn" data-wow-delay="0.3s">The Next Event Could Change Someone&rsquo;s Story</h1>
                        <p class="fs-5 mb-4 wow fadeIn" data-wow-delay="0.5s">A walk can spark awareness. A clinic can free a family from fear. A volunteer day can make a street safer. Stand with Stand For Humanity Foundation — in person, and with purpose.</p>
                        <div class="d-flex justify-content-center flex-wrap gap-2 wow fadeIn" data-wow-delay="0.7s">
                            <a class="btn btn-primary py-3 px-4 me-lg-3" href="{{ route('donate') }}">Donate Now</a>
                            <a class="btn btn-secondary py-3 px-4" href="{{ route('volunteer') }}">Join Us Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    @include('site.partials.newsletter', ['heading' => 'Subscribe to Our Newsletter', 'copy' => 'Updates on our programmes, volunteer calls, and ways to stand with people in need — no spam.'])

@include('site.partials.footer')


    <!-- Back to Top -->
    <a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('Charitize/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('Charitize/lib/counterup/counterup.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('Charitize/js/main.js') }}?v=impact-video"></script>
    <script src="{{ asset('Charitize/js/cookie-banner.js') }}" defer></script>
</body>

</html>
