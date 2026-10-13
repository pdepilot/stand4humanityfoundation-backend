<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donate | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Stand For Humanity Foundation, child protection, victim support, humanitarian assistance, Nigeria, Owerri, Imo State" name="keywords">
    <meta content="Support child protection, emergency rescue, and victim support. Donate to Stand For Humanity Foundation in Owerri, Imo State." name="description">
    <meta property="og:title" content="Donate | Stand For Humanity Foundation">
    <meta property="og:description" content="Support child protection, victim care, and humanitarian assistance.">

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
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-4">
            <h1 class="display-3 animated slideInDown">Donate</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="#!">More</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Donate</li>
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


    <!-- Donation Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeIn" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">Donate</p>
                <h1 class="display-6 mb-4">Support Protection, Rescue &amp; Humanitarian Care</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                    <div class="donation-item d-flex h-100 p-4">
                        @include('site.partials.donation-goal-meter', ['progress' => $donationProgress->for(\App\Enums\DonationCause::ChildProtection)])
                        <div class="donation-detail">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="{{ asset('Charitize/img/donation-1.jpg') }}" alt="Child protection and rescue donation" loading="lazy">
                                <a href="#!"
                                    class="btn btn-sm btn-secondary px-3 position-absolute top-0 end-0">Protect</a>
                            </div>
                            <a href="#!" class="h3 d-inline-block">Child Protection & Rescue</a>
                            <p>Your gift helps us support and rescue children facing abuse, neglect, or unsafe living conditions.</p>
                            <a href="#!" class="btn btn-primary w-100 py-3"><i class="fa fa-plus me-2"></i>Donate
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.13s">
                    <div class="donation-item d-flex h-100 p-4">
                        @include('site.partials.donation-goal-meter', ['progress' => $donationProgress->for(\App\Enums\DonationCause::Rescue)])
                        <div class="donation-detail">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="{{ asset('Charitize/img/donation-2.jpg') }}" alt="Rescue mission donation" loading="lazy">
                                <a href="#!"
                                    class="btn btn-sm btn-secondary px-3 position-absolute top-0 end-0">Rescue</a>
                            </div>
                            <a href="#!" class="h3 d-inline-block">Emergency Rescue &amp; Intervention</a>
                            <p>Support assistance when individuals are exposed to serious danger or vulnerability, including victims of kidnapping.</p>
                            <a href="#!" class="btn btn-primary w-100 py-3"><i class="fa fa-plus me-2"></i>Donate
                                Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                    <div class="donation-item d-flex h-100 p-4">
                        @include('site.partials.donation-goal-meter', ['progress' => $donationProgress->for(\App\Enums\DonationCause::VictimSupport)])
                        <div class="donation-detail">
                            <div class="position-relative mb-4">
                                <img class="img-fluid w-100" src="{{ asset('Charitize/img/donation-3.jpg') }}" alt="Emergency response donation" loading="lazy">
                                <a href="#!"
                                    class="btn btn-sm btn-secondary px-3 position-absolute top-0 end-0">Support</a>
                            </div>
                            <a href="#!" class="h3 d-inline-block">Victim Support &amp; Advocacy</a>
                            <p>Give a voice to people who have been victimized, harassed, or otherwise unable to speak for themselves.</p>
                            <a href="#!" class="btn btn-primary w-100 py-3"><i class="fa fa-plus me-2"></i>Donate
                                Now</a>
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
                        <h1 class="display-6 mb-4">Your Gift Helps People Who Cannot Stand Alone</h1>
                        <p class="fs-5 mb-0">Every contribution to Stand For Humanity Foundation supports child protection, victim care, emergency intervention, and humanitarian assistance for people in need.</p>
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
                                    <button class="btn btn-secondary py-3 w-100" type="submit">Donate
                                        Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Donate End -->

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
    <script src="{{ asset('Charitize/js/main.js') }}?v=impact-video"></script>
    <script src="{{ asset('Charitize/js/cookie-banner.js') }}" defer></script>
    <script src="{{ asset('Charitize/js/donate-capture.js') }}?v=live-1"></script>
</body>

</html>