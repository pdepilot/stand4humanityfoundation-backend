<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stand For Humanity Foundation | Owerri, Imo State, Nigeria</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Stand For Humanity Foundation, Owerri, Imo State, Nigeria, child protection, domestic abuse, victim support, humanitarian assistance, advocacy" name="keywords">
    <meta content="Stand For Humanity Foundation is a humanitarian organization in Owerri, Imo State, Nigeria. We give voice to the voiceless, protect children, support victims of abuse, and help people in need." name="description">
    <meta property="og:title" content="Stand For Humanity Foundation | Protecting the Vulnerable">
    <meta property="og:description" content="We stand with those who cannot stand alone — protecting children, supporting survivors, and helping people in need in Owerri, Imo State, Nigeria.">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <script type="application/ld+json">
    {"@@context":"https://schema.org","@@type":"NGO","name":"Stand For Humanity Foundation","url":"https://www.standforhumanityfoundation.org/","email":"info@standforhumanityfoundation.org","telephone":"+234-800-723-3248","address":{"@@type":"PostalAddress","addressLocality":"Owerri","addressRegion":"Imo State","addressCountry":"NG"},"description":"A humanitarian organization committed to protecting vulnerable people, giving voice to the voiceless, and providing support to individuals and families facing difficult circumstances."}
    </script>

    <!-- Favicon -->
    <link href="{{ asset('images/portal/stand-logo.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap" rel="stylesheet">

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
    <link href="{{ asset('Charitize/css/news.css') }}?v=journal" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-secondary top-bar wow fadeIn" data-wow-delay="0.1s">
        <div class="row align-items-center h-100">
            <div class="col-12 col-lg-auto text-center text-lg-start pe-lg-4">
                <a href="{{ route('home') }}" aria-label="Stand For Humanity Foundation home">
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
                <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav me-auto">
                        <a href="{{ route('home') }}" class="nav-item nav-link active">Home</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link">About</a>
                        <a href="{{ route('services') }}" class="nav-item nav-link">Services</a>
                        <a href="{{ route('donations') }}" class="nav-item nav-link">Donations</a>
                        <a href="{{ route('news') }}" class="nav-item nav-link">News</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">More</a>
                            <div class="dropdown-menu more-menu m-0">
                                <a href="{{ route('mission') }}" class="dropdown-item">
                                    <strong>Our Mission</strong>
                                    <span>Protecting the vulnerable and the voiceless</span>
                                </a>
                                <a href="{{ route('events') }}" class="dropdown-item">
                                    <strong>Events</strong>
                                    <span>Awareness and community gatherings</span>
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


    <!-- Carousel Start -->
    <div class="container-fluid p-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="owl-carousel header-carousel py-5">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="carousel-text">
                            <h1 class="display-1 text-uppercase mb-3">Standing for Humanity. Protecting the Vulnerable.</h1>
                            <p class="fs-5 mb-5">Giving voice to the voiceless, standing with victims, protecting children, supporting survivors, and helping those in need. We stand with those who cannot stand alone.</p>
                            <div class="d-flex">
                                <a class="btn btn-primary py-3 px-4 me-3" href="{{ route('donate') }}">Donate</a>
                                <a class="btn btn-secondary py-3 px-4" href="{{ route('volunteer') }}">Become a Volunteer</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="carousel-img">
                            <img class="w-100" src="{{ asset('Charitize/img/stand2.jpg') }}" alt="Community volunteers supporting public safety and human rights">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="carousel-text">
                            <h1 class="display-1 text-uppercase mb-3">Giving Voice to the Voiceless</h1>
                            <p class="fs-5 mb-5">Based in Owerri, Imo State, Nigeria, we stand with victims of abuse and harassment, rescue children from unsafe situations, and assist people affected by kidnapping and other forms of victimization.</p>
                            <div class="d-flex mt-4">
                                <a class="btn btn-primary py-3 px-4 me-3" href="{{ route('report') }}">Report a Case</a>
                                <a class="btn btn-secondary py-3 px-4" href="{{ route('about') }}">Learn More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="carousel-img">
                            <img class="w-100" src="{{ asset('Charitize/img/stand5.jpg') }}" alt="People united for community safety and humanitarian action">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="carousel-text">
                            <h1 class="display-1 text-uppercase mb-3">We Stand With Those Who Cannot Stand Alone</h1>
                            <p class="fs-5 mb-5">Through advocacy, intervention, rescue efforts, and community support, we work for safer communities where every person can live with dignity, protection, hope, and a sense of belonging.</p>
                            <div class="d-flex mt-4">
                                <a class="btn btn-primary py-3 px-4 me-3" href="{{ route('volunteer') }}">Volunteer With Us</a>
                                <a class="btn btn-secondary py-3 px-4" href="{{ route('mission') }}">Our Programs</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="carousel-img">
                            <img class="w-100" src="{{ asset('Charitize/img/stand4.jpg') }}" alt="foundation volunteers building safer communities together">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->


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
                        <h3 class="ms-5 mb-0">We stand with those who cannot stand alone — protecting children, supporting survivors, and helping people in need.</h3>
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
                    <h5 class="modal-title" id="exampleModalLabel">Foundation Impact Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- 16:9 aspect ratio -->
                    <div class="ratio ratio-16x9">
                        <iframe class="embed-responsive-item" src="" id="video" allowfullscreen allowscriptaccess="always"
                            allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Video Modal End -->


    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.2s">
                    <div class="about-img">
                        <img class="img-fluid w-100" src="{{ asset('Charitize/img/stand4.jpg') }}" alt="Stand For Humanity Foundation supporting vulnerable people in Owerri" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-6">
                    <p class="section-title bg-white text-start text-primary pe-3">About</p>
                    <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.2s">Standing for Humanity in Owerri, Imo State</h1>
                    <p class="mb-4 wow fadeIn" data-wow-delay="0.3s">Stand For Humanity Foundation is a humanitarian organization based in Owerri, Imo State, Nigeria, committed to protecting vulnerable people, defending those whose voices are often unheard, and providing support to individuals and families facing difficult circumstances.</p>
                    <div class="row g-4 pt-2">
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.4s">
                            <div class="h-100">
                                <h3>Our Mission</h3>
                                <p>To protect the vulnerable, give voice to the voiceless, rescue those in danger, support victims of abuse and violence, and provide meaningful assistance to people in need.</p>
                                <p class="text-dark"><i class="fa fa-check text-primary me-2"></i>Child protection and rescue</p>
                                <p class="text-dark"><i class="fa fa-check text-primary me-2"></i>Support for victims of abuse</p>
                                <p class="text-dark mb-0"><i class="fa fa-check text-primary me-2"></i>Humanitarian help for people in need</p>
                            </div>
                        </div>
                        <div class="col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                            <div class="h-100 bg-primary p-4 text-center">
                                <p class="fs-5 text-dark">Your support helps us protect children, stand with victims of abuse, and assist people who cannot stand alone.</p>
                                <a class="btn btn-secondary py-2 px-4" href="{{ route('donate') }}">Donate</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


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
                                    <h1 class="display-5 text-white mb-0">Rescue</h1>
                                    <span class="text-white">Emergency Intervention</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="text-center bg-secondary py-5 px-4 h-100">
                                    <i class="fa fa-list-check fa-3x text-primary mb-3"></i> 
                                    <h1 class="display-5 text-white mb-0">Voice</h1>
                                    <span class="text-white">Victim Support</span>
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
                    <p class="section-title bg-white text-start text-primary pe-3">Why Us!</p>
                    <h1 class="display-6 mb-4 wow fadeIn" data-wow-delay="0.2s">Why Communities Partner With Us</h1>
                    <p class="mb-4 wow fadeIn" data-wow-delay="0.3s">We give voice to the voiceless, stand with victims of abuse and harassment, and extend help to those who are helpless and in need.</p>
                    <p class="text-dark wow fadeIn" data-wow-delay="0.4s"><i class="fa fa-check text-primary me-2"></i>Protection for children and vulnerable people</p>
                    <p class="text-dark wow fadeIn" data-wow-delay="0.5s"><i class="fa fa-check text-primary me-2"></i>Support for survivors of domestic abuse</p>
                    <p class="text-dark wow fadeIn" data-wow-delay="0.6s"><i class="fa fa-check text-primary me-2"></i>Humanitarian assistance for people in hardship</p>
                    <div class="d-flex mt-4 wow fadeIn" data-wow-delay="0.7s">
                        <a class="btn btn-primary py-3 px-4 me-3" href="{{ route('donate') }}">Donate</a>
                        <a class="btn btn-secondary py-3 px-4" href="{{ route('volunteer') }}">Become a Volunteer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->


    <!-- Donation Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">Programs</p>
                <h1 class="display-6 mb-4">Support Our Priority Initiatives</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="donation-item d-flex h-100 p-4">
                        @include('site.partials.donation-goal-meter', ['progress' => $donationProgress->for(\App\Enums\DonationCause::ChildProtection)])
                        <div class="donation-detail">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="{{ asset('Charitize/img/donation-1.jpg') }}" alt="Child protection and rescue support" loading="lazy">
                                <a href="#" class="btn btn-sm btn-secondary px-3 position-absolute top-0 end-0">Protect</a>
                            </div>
                            <a href="{{ route('donate') }}" class="h3 d-inline-block">Child Protection & Rescue</a>
                            <p>Support work that helps children facing abuse, neglect, or unsafe living conditions.</p>
                            <a href="{{ route('donate') }}" class="btn btn-primary w-100 py-3"><i class="fa fa-plus me-2"></i>Donate</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.13s">
                    <div class="donation-item d-flex h-100 p-4">
                        @include('site.partials.donation-goal-meter', ['progress' => $donationProgress->for(\App\Enums\DonationCause::Rescue)])
                        <div class="donation-detail">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="{{ asset('Charitize/img/donation-2.jpg') }}" alt="Rescue and emergency response volunteers" loading="lazy">
                                <a href="#" class="btn btn-sm btn-secondary px-3 position-absolute top-0 end-0">Rescue</a>
                            </div>
                            <a href="{{ route('donate') }}" class="h3 d-inline-block">Emergency Rescue</a>
                            <p>Help us respond when people are in danger, including victims of kidnapping and other serious harm.</p>
                            <a href="{{ route('donate') }}" class="btn btn-primary w-100 py-3"><i class="fa fa-plus me-2"></i>Donate</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                    <div class="donation-item d-flex h-100 p-4">
                        @include('site.partials.donation-goal-meter', ['progress' => $donationProgress->for(\App\Enums\DonationCause::VictimSupport)])
                        <div class="donation-detail">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="{{ asset('Charitize/img/donation-3.jpg') }}" alt="Victim support and community outreach" loading="lazy">
                                <a href="#" class="btn btn-sm btn-secondary px-3 position-absolute top-0 end-0">Support</a>
                            </div>
                            <a href="{{ route('donate') }}" class="h3 d-inline-block">Victim Support</a>
                            <p>Enable practical help for people who have been abused, harassed, or otherwise victimized.</p>
                            <a href="{{ route('donate') }}" class="btn btn-primary w-100 py-3"><i class="fa fa-plus me-2"></i>Donate</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Donation End -->


    <!-- Donate Start -->
    <div class="container-fluid donate py-5">
        <div class="container">
            <div class="row g-0">
                <div class="col-lg-7 donate-text bg-light py-5 wow fadeIn" data-wow-delay="0.1s">
                    <div class="d-flex flex-column justify-content-center h-100 p-5 wow fadeIn" data-wow-delay="0.3s">
                        <h1 class="display-6 mb-4">Support Protection, Rescue &amp; Humanitarian Care</h1>
                        <p class="fs-5 mb-0">Your contribution strengthens child protection, victim support, emergency intervention, and assistance for people in need.</p>
                    </div>
                </div>
                <div class="col-lg-5 donate-form bg-primary py-5 text-center wow fadeIn" data-wow-delay="0.5s">
                    <div class="h-100 p-5">
                        <form id="donationCaptureForm">
                            <div id="donationAlert" class="alert alert-danger @unless (session('payment_error')) d-none @endunless text-start" role="alert">{{ session('payment_error') }}</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                                        <label for="name">Full Name</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                                        <label for="email">Email Address</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
                                        <label for="phone">Phone Number</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="currency" name="currency" required>
                                            <option value="NGN" selected>NGN (&#8358;)</option>
                                            <option value="USD">USD ($)</option>
                                            <option value="GBP">GBP (£)</option>
                                            <option value="EUR">EUR (€)</option>
                                        </select>
                                        <label for="currency">Currency</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Amount" min="1" step="1" required>
                                        <label for="amount">Donation Amount</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <select class="form-select" id="cause" name="cause" required>
                                            <option value="" selected disabled>Select a cause</option>
                                            <option value="child-protection">Child Protection & Rescue</option>
                                            <option value="rescue">Emergency Rescue & Intervention</option>
                                            <option value="victim-support">Victim Support & Advocacy</option>
                                            <option value="community-awareness">Community Awareness & Advocacy</option>
                                            <option value="general">General Donation</option>
                                        </select>
                                        <label for="cause">Donation Cause</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <select class="form-select" id="frequency" name="frequency" required>
                                            <option value="one-time" selected>One-time</option>
                                            <option value="monthly">Monthly</option>
                                            <option value="quarterly">Quarterly</option>
                                            <option value="yearly">Yearly</option>
                                        </select>
                                        <label for="frequency">Donation Frequency</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="location" name="location" placeholder="City / Country">
                                        <label for="location">City / Country</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="message" name="message" placeholder="Message" style="height: 100px;"></textarea>
                                        <label for="message">Message / Dedication (optional)</label>
                                    </div>
                                </div>
                                <div class="col-12 text-start">
                                    <p class="mb-2 text-dark fw-semi-bold">Payment Method</p>
                                    <div class="payment-methods" role="radiogroup" aria-label="Payment Method">
                                        <div class="payment-method-option">
                                            <input type="radio" class="btn-check" name="payment_method" id="paystack" value="paystack" autocomplete="off" required checked>
                                            <label class="payment-method-btn" for="paystack">
                                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                                                <span>Paystack</span>
                                            </label>
                                        </div>
                                        <div class="payment-method-option">
                                            <input type="radio" class="btn-check" name="payment_method" id="flutterwave" value="flutterwave" autocomplete="off">
                                            <label class="payment-method-btn" for="flutterwave">
                                                <i class="fa fa-wallet" aria-hidden="true"></i>
                                                <span>Flutterwave</span>
                                            </label>
                                        </div>
                                    </div>
                                    <p class="payment-method-status" id="paymentMethodStatus" aria-live="polite">Paystack selected</p>
                                </div>
                                <div class="col-12 text-start">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="anonymous" name="anonymous">
                                        <label class="form-check-label text-dark" for="anonymous">
                                            Make this donation anonymous
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-secondary py-3 w-100" type="submit">Donate Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Donate End -->


    <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">Our Team</p>
                <h1 class="display-6 mb-4">Leadership & Volunteer Coordination</h1>
            </div>
            @include('site.partials.team-roster')
        </div>
    </div>
    <!-- Team End -->


    @include('site.partials.testimonies')


    <!-- Latest News Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 560px;">
                <p class="section-title bg-white text-center text-primary px-3">Newsroom</p>
                <h1 class="display-6 mb-4">Latest from the Foundation</h1>
                <p class="mb-0">Programmes and updates from Stand For Humanity Foundation in Owerri, Imo State.</p>
            </div>
            <div class="row g-4 mt-2">
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <article class="home-news-card">
                        <img src="{{ asset('Charitize/img/stand2.jpg') }}" alt="Child protection and family support" loading="lazy">
                        <div class="p-4">
                            <span class="news-category mb-3">Protection</span>
                            <div class="news-meta my-2">
                                <span><i class="fa fa-location-dot me-2"></i>Owerri, Imo State</span>
                            </div>
                            <h3 class="h4"><a href="{{ route('news.show', 'child-protection') }}" class="text-dark">Child protection and rescue: how we stand with children in danger</a></h3>
                            <p>We support and rescue children facing abuse, exploitation, neglect, or unsafe living conditions — with care and confidentiality.</p>
                            <a class="btn btn-primary py-2 px-4" href="{{ route('news.show', 'child-protection') }}">Read more</a>
                        </div>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.25s">
                    <article class="home-news-card">
                        <img src="{{ asset('Charitize/img/pic1.jpg') }}" alt="Support for victims of abuse" loading="lazy">
                        <div class="p-4">
                            <span class="news-category mb-3">Support</span>
                            <div class="news-meta my-2">
                                <span><i class="fa fa-location-dot me-2"></i>Owerri, Imo State</span>
                            </div>
                            <h3 class="h4"><a href="{{ route('news.show', 'domestic-abuse') }}" class="text-dark">Domestic abuse intervention: helping people seek safety</a></h3>
                            <p>We support victims of domestic violence and help vulnerable individuals seek safety and appropriate assistance.</p>
                            <a class="btn btn-primary py-2 px-4" href="{{ route('news.show', 'domestic-abuse') }}">Read more</a>
                        </div>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.4s">
                    <article class="home-news-card">
                        <img src="{{ asset('Charitize/img/stand4.jpg') }}" alt="Advocacy and victim support" loading="lazy">
                        <div class="p-4">
                            <span class="news-category mb-3">Advocacy</span>
                            <div class="news-meta my-2">
                                <span><i class="fa fa-location-dot me-2"></i>Owerri, Imo State</span>
                            </div>
                            <h3 class="h4"><a href="{{ route('news.show', 'victim-advocacy') }}" class="text-dark">Victim support and advocacy for people who cannot speak for themselves</a></h3>
                            <p>We give a voice to people who have been victimized, harassed, threatened, or otherwise unable to speak for themselves.</p>
                            <a class="btn btn-primary py-2 px-4" href="{{ route('news.show', 'victim-advocacy') }}">Read more</a>
                        </div>
                    </article>
                </div>
            </div>
            <div class="text-center mt-5 wow fadeIn" data-wow-delay="0.2s">
                <a class="btn btn-secondary py-3 px-4" href="{{ route('news') }}">Read the journal</a>
            </div>
        </div>
    </div>
    <!-- Latest News End -->

    @include('site.partials.newsletter', ['heading' => 'Subscribe to Our Newsletter', 'copy' => 'Updates on our programmes, volunteer calls, and ways to stand with people in need — no spam.'])

@include('site.partials.footer')


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top" aria-label="Back to top"><i class="bi bi-arrow-up"></i></a>


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
    <script src="{{ asset('Charitize/js/donate-capture.js') }}?v=live-1"></script>
    <script src="{{ asset('Charitize/js/testimony-capture.js') }}?v=reveal"></script>
</body>

</html>