<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donations Ledger | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="donations ledger, transparency, Stand For Humanity Foundation, donor records, Owerri, Imo State, Nigeria" name="keywords">
    <meta content="View real-time donation activity with donor names, masked phone numbers, partial descriptions, bank balance, and amount spent so far." name="description">
    <meta property="og:title" content="Donations Ledger | Stand For Humanity Foundation">
    <meta property="og:description" content="Transparent, real-time donation records for Stand For Humanity Foundation.">

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
    <link href="{{ asset('Charitize/css/donations.css') }}?v=live-1" rel="stylesheet">
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
                        <a href="{{ route('donations') }}" class="nav-item nav-link active">Donations</a>
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
                                <a href="{{ route('donate') }}" class="dropdown-item">
                                    <strong>Give Now</strong>
                                    <span>Support child protection and victim care</span>
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
            <h1 class="display-3 animated slideInDown">Donations</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Donations</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Live Intro Start -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h2 class="display-6 mb-3">Live Donation Transparency</h2>
                    <p class="fs-5 mb-2">When you donate, your name appears on this ledger immediately. Amounts of any size are shown in real time.</p>
                    <p class="fs-5 mb-0" id="latestDonorLive">The next donation will appear here instantly.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="donations-live-badge mb-2">
                        <span class="pulse-dot" aria-hidden="true"></span>
                        <strong>LIVE</strong>
                    </div>
                    <div class="fs-5" id="liveClock">—</div>
                    <div class="small" id="lastUpdated">Updating…</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Live Intro End -->


    <!-- Totals Start -->
    <div class="container-fluid pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.05s">
                    <div class="donation-stat-card text-center bg-primary py-5 px-3 h-100">
                        <i class="fa fa-bullseye fa-3x text-secondary mb-3"></i>
                        <h6 class="text-dark mb-2">Donation Goal</h6>
                        <h2 class="display-6 mb-0" id="donationGoal">&#8358;{{ $donationProgress->formattedGoal() }}</h2>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="donation-stat-card text-center bg-primary py-5 px-3 h-100">
                        <i class="fa fa-vault fa-3x text-secondary mb-3"></i>
                        <h6 class="text-dark mb-2">Total in Bank</h6>
                        <h2 class="display-6 mb-0" id="totalInBank">&#8358;{{ $donationProgress->formattedBank() }}</h2>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.2s">
                    <div class="donation-stat-card text-center bg-secondary py-5 px-3 h-100">
                        <i class="fa fa-money-bill-transfer fa-3x text-primary mb-3"></i>
                        <h6 class="text-white mb-2">Amount Spent So Far</h6>
                        <h2 class="display-6 text-white mb-0" id="amountSpent">&#8358;{{ $donationProgress->formattedSpent() }}</h2>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.3s">
                    <div class="donation-stat-card text-center bg-secondary py-5 px-3 h-100">
                        <i class="fa fa-hand-holding-dollar fa-3x text-primary mb-3"></i>
                        <h6 class="text-white mb-2">Total Received</h6>
                        <h2 class="display-6 text-white mb-0" id="totalReceived">&#8358;{{ $donationProgress->formattedRaised() }}</h2>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 wow fadeIn" data-wow-delay="0.4s">
                    <div class="donation-stat-card text-center bg-primary py-5 px-3 h-100">
                        <i class="fa fa-users fa-3x text-secondary mb-3"></i>
                        <h6 class="text-dark mb-2">Recorded Donors</h6>
                        <h2 class="display-6 mb-0" id="donorCount">0</h2>
                    </div>
                </div>
            </div>
            <p class="small text-muted mt-3 mb-0">Bank totals are shown in NGN. Foreign-currency gifts are converted using demo exchange rates for transparency display.</p>
        </div>
    </div>
    <!-- Totals End -->


    <!-- Ledger Table Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-3 wow fadeIn" data-wow-delay="0.1s">
                <div>
                    <p class="section-title bg-white text-start text-primary pe-3">Ledger</p>
                    <h1 class="display-6 mb-0">Recent Donations</h1>
                </div>
                <a class="btn btn-primary py-3 px-4" href="{{ route('donate') }}">Make a Donation</a>
            </div>

            <div id="donorThankYou" class="donor-thank-you p-4 mb-4 d-none wow fadeIn" role="status" aria-live="polite">
                <h4 class="mb-2">Your donation is live</h4>
                <p class="fs-5 mb-0"><strong id="donorThankYouName"></strong> — <span id="donorThankYouAmount"></span> is now listed below.</p>
            </div>

            <div class="donations-table-wrap bg-white p-3 p-md-4 wow fadeIn" data-wow-delay="0.2s">
                <div id="donationsEmpty" class="text-center py-5 d-none">
                    <p class="mb-3">No donations recorded yet.</p>
                    <a class="btn btn-secondary py-2 px-4" href="{{ route('donate') }}">Be the First Donor</a>
                </div>
                <div class="table-responsive">
                    <table class="table donations-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">SN</th>
                                <th scope="col">Date &amp; Time</th>
                                <th scope="col">Name</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Description</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="donationsTableBody">
                            <!-- Filled in real time by donations.js -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Ledger Table End -->


    <!-- Banner Start -->
    <div class="container-fluid banner py-5">
        <div class="container">
            <div class="banner-inner bg-light p-5 wow fadeIn" data-wow-delay="0.1s">
                <div class="row justify-content-center">
                    <div class="col-lg-8 py-5 text-center">
                        <h1 class="display-6 wow fadeIn" data-wow-delay="0.3s">Your Gift Becomes Visible Progress</h1>
                        <p class="fs-5 mb-4 wow fadeIn" data-wow-delay="0.5s">Every donation supports child protection, victim care, emergency intervention, and help for people in need — and appears on this ledger with privacy protections for donors.</p>
                        <div class="d-flex justify-content-center flex-wrap gap-2 wow fadeIn" data-wow-delay="0.7s">
                            <a class="btn btn-primary py-3 px-4 me-lg-3" href="{{ route('donate') }}">Donate Now</a>
                            <a class="btn btn-secondary py-3 px-4" href="{{ route('report') }}">Report Live</a>
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
    <script src="{{ asset('Charitize/js/donations.js') }}?v=live-1"></script>
</body>

</html>
