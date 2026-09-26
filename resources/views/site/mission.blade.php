<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Our Mission & Programs | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Stand For Humanity Foundation, mission, vision, child protection, victim support, humanitarian assistance, Owerri, Imo State, Nigeria" name="keywords">
    <meta content="Our mission is to protect the vulnerable, give voice to the voiceless, rescue those in danger, and help people in need in Owerri, Imo State, Nigeria." name="description">
    <meta property="og:title" content="Our Mission & Programs | Stand For Humanity Foundation">
    <meta property="og:description" content="Child protection, domestic abuse intervention, victim support, emergency rescue, humanitarian assistance, and community advocacy.">

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
                                <a href="{{ route('events') }}" class="dropdown-item">
                                    <strong>Events</strong>
                                    <span>Awareness and community gatherings</span>
                                </a>
                                <a href="{{ route('mission') }}" class="dropdown-item active">
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
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-4">
            <h1 class="display-3 animated slideInDown">Our Mission</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#!">More</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Our Mission</li>
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
                        <button type="button" class="btn-play" data-bs-toggle="modal"
                            data-src="https://www.youtube.com/embed/DWRcNpR6Kdc" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                        <h3 class="ms-5 mb-0">We stand with those who cannot stand alone.
                        </h3>
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
                    <!-- 16:9 aspect ratio -->
                    <div class="ratio ratio-16x9">
                        <iframe class="embed-responsive-item" src="" id="video" allowfullscreen
                            allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Video Modal End -->


    <!-- Features Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="rounded overflow-hidden">
                        <div class="row g-0">
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                                <div class="text-center bg-primary py-5 px-4 h-100">
                                    <i class="fa fa-users fa-3x text-secondary mb-3"></i>
                                    <h1 class="display-5 mb-0">Care</h1>
                                    <span class="text-dark">Child Protection</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                                <div class="text-center bg-secondary py-5 px-4 h-100">
                                    <i class="fa fa-award fa-3x text-primary mb-3"></i>
                                    <h1 class="display-5 text-white mb-0">Voice</h1>
                                    <span class="text-white">Victim Support</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="text-center bg-secondary py-5 px-4 h-100">
                                    <i class="fa fa-list-check fa-3x text-primary mb-3"></i>
                                    <h1 class="display-5 text-white mb-0">Rescue</h1>
                                    <span class="text-white">Emergency Intervention</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.7s">
                                <div class="text-center bg-primary py-5 px-4 h-100">
                                    <i class="fa fa-comments fa-3x text-secondary mb-3"></i>
                                    <h1 class="display-5 mb-0">Help</h1>
                                    <span class="text-dark">Support for the Needy</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <p class="section-title bg-white text-start text-primary pe-3">Our Mission</p>
                    <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.2s">Our Mission and Vision</h1>
                    <p class="mb-4 wow fadeIn" data-wow-delay="0.3s">To protect the vulnerable, give voice to the voiceless, rescue those in danger, support victims of abuse and violence, and provide meaningful assistance to people in need. We work toward a society where every person, especially children and vulnerable individuals, is protected from abuse, treated with dignity, and given the opportunity to live a safe and meaningful life.</p>
                    <p class="text-dark wow fadeIn" data-wow-delay="0.4s"><i
                            class="fa fa-check text-primary me-2"></i>Child Protection &amp; Rescue</p>
                    <p class="text-dark wow fadeIn" data-wow-delay="0.5s"><i
                            class="fa fa-check text-primary me-2"></i>Domestic Abuse Intervention &amp; Victim Support</p>
                    <p class="text-dark wow fadeIn" data-wow-delay="0.6s"><i
                            class="fa fa-check text-primary me-2"></i>Humanitarian assistance across Imo State</p>
                    <div class="d-flex mt-4 wow fadeIn" data-wow-delay="0.7s">
                        <a class="btn btn-primary py-3 px-4 me-3" href="{{ route('donate') }}">Donate Now</a>
                        <a class="btn btn-secondary py-3 px-4" href="{{ route('volunteer') }}">Join Us Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->


    <!-- Banner Start -->
    <div class="container-fluid banner py-5">
        <div class="container">
            <div class="banner-inner bg-light p-5 wow fadeIn" data-wow-delay="0.1s">
                <div class="row justify-content-center">
                    <div class="col-lg-8 py-5 text-center">
                        <h1 class="display-6 wow fadeIn" data-wow-delay="0.3s">Stand With Those Who Cannot Stand Alone</h1>
                        <p class="fs-5 mb-4 wow fadeIn" data-wow-delay="0.5s">From child protection and victim support to emergency intervention and help for people in need, our mission is to protect the vulnerable and restore dignity.</p>
                        <div class="d-flex justify-content-center wow fadeIn" data-wow-delay="0.7s">
                            <a class="btn btn-primary py-3 px-4 me-3" href="{{ route('donate') }}">Donate Now</a>
                            <a class="btn btn-secondary py-3 px-4" href="{{ route('volunteer') }}">Join Us Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    @include('site.partials.newsletter', ['heading' => 'Stay Informed on Our Work', 'copy' => 'Receive updates on our programmes, volunteer opportunities, and ways to help people in need.'])

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
    <script src="{{ asset('Charitize/js/main.js') }}?v=nav-more"></script>
    <script src="{{ asset('Charitize/js/cookie-banner.js') }}" defer></script>
</body>

</html>