<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Us | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Stand For Humanity Foundation, child protection, victim support, humanitarian assistance, Nigeria, Owerri, Imo State" name="keywords">
    <meta content="Contact our Owerri office for child protection, victim support, volunteer opportunities, or emergency help. Call {{ $siteProfile->phone }}." name="description">
    <meta property="og:title" content="Contact Us | Stand For Humanity Foundation">
    <meta property="og:description" content="Contact Stand For Humanity Foundation in Owerri, Imo State. Call {{ $siteProfile->phone }}.">

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
    <style>
        .report-honeypot {
            position: absolute !important;
            left: -9999px !important;
            width: 1px !important;
            height: 1px !important;
            overflow: hidden !important;
            opacity: 0 !important;
        }
    </style>
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
                        <a href="{{ route('contact') }}" class="nav-item nav-link active">Contact</a>
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
            <h1 class="display-3 animated slideInDown">Contact</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#!">More</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
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
                        <h3 class="ms-5 mb-0">Together, we defend human rights and keep communities safe.
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


    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
                    <p class="section-title bg-white text-start text-primary pe-3">Contact</p>
                    <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.2s">Request Assistance ? We Are Here to Help</h1>
                    <iframe class="w-100"
                        src="https://www.google.com/maps?q=Owerri,+Imo+State,+Nigeria&output=embed"
                        title="Map of Owerri, Imo State, Nigeria"
                        frameborder="0" style="height: 425px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                </div>
                <div class="col-lg-7 wow fadeIn" data-wow-delay="0.3s">
                    <h3>Reach Our Owerri Office</h3>
                    <p class="mb-4">For child protection, victim support, emergency help, or volunteer inquiries, contact us by phone at <strong>{{ $siteProfile->phone }}</strong> or email <strong>{{ $siteProfile->email }}</strong>. Emergency assistance is available through our hotline.</p>
                    <div id="contactAlert" class="alert d-none" role="status"></div>
                    <form id="contactCaptureForm" method="post" action="{{ route('contacts.api.store') }}" novalidate>
                        @csrf
                        <div class="report-honeypot" aria-hidden="true">
                            <label for="website">Website</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required maxlength="80" autocomplete="name">
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required maxlength="120" autocomplete="email">
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Type of Assistance" required maxlength="160">
                                    <label for="subject">Type of Assistance</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Describe your situation or request" id="message" name="message"
                                        style="height: 250px" required minlength="10" maxlength="5000"></textarea>
                                    <label for="message">Describe Your Request</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary py-3 px-4" type="submit" id="contactSubmitBtn" data-original-text="Request Assistance">Request Assistance</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

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
    <script src="{{ asset('Charitize/js/contact-capture.js') }}"></script>
</body>

</html>