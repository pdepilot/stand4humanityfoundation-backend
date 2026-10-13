<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Terms of Use | Stand For Humanity Foundation</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Stand For Humanity Foundation terms of use, website rules, Owerri, Nigeria" name="keywords">
    <meta content="Terms of Use for the Stand For Humanity Foundation website, including acceptable use, programme notes, and reporting." name="description">
    <meta property="og:title" content="Terms of Use | Stand For Humanity Foundation">
    <meta property="og:description" content="Terms of Use for the Stand For Humanity Foundation website.">
    <link href="{{ asset('Charitize/img/favicon.ico') }}" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@600;700&family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('Charitize/lib/animate/animate.min.css') }}" rel="stylesheet">
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
                <a href="{{ route('home') }}"><img src="{{ asset('Charitize/img/stand-logo.png') }}?v=blue" alt="Stand For Humanity Foundation" class="m-0" style="height: 80px;"></a>
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
                <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav me-auto">
                        <a href="{{ route('home') }}" class="nav-item nav-link">Home</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link">About</a>
                        <a href="{{ route('services') }}" class="nav-item nav-link">Services</a>
                        <a href="{{ route('donations') }}" class="nav-item nav-link">Donations</a>
                        <a href="{{ route('news') }}" class="nav-item nav-link">News</a>
                        <a href="{{ route('contact') }}" class="nav-item nav-link">Contact</a>
                        <a href="{{ route('terms') }}" class="nav-item nav-link active">Terms</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container text-center py-4">
            <h1 class="display-3 animated slideInDown">Terms of Use</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Terms</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <article class="blog-article">
                        <div class="blog-article-body">
                            <p>These Terms of Use govern your access to the Stand For Humanity Foundation website. By using the site, you agree to these terms.</p>
                            <h2>Purpose of the site</h2>
                            <p>This website shares original programme information, accepts donations and volunteer interest, and provides channels to request help or report urgent cases. It is not a substitute for emergency services. If someone is in immediate physical danger, contact the Nigeria Police in parallel.</p>
                            <h2>Programme notes</h2>
                            <p>News and programme notes are original writing about our work. They are educational and informational. They are not medical, legal, or case advice for a specific person. Photographs show public programme activity and do not identify minors or survivors.</p>
                            <h2>Acceptable use</h2>
                            <ul>
                                <li>Do not misuse forms to harass, threaten, or submit false reports.</li>
                                <li>Do not attempt to disrupt the site, scrape confidential case data, or bypass security controls.</li>
                                <li>Do not upload unlawful, abusive, or identifying content about children or survivors.</li>
                            </ul>
                            <h2>Donations and volunteering</h2>
                            <p>Donations support foundation programmes. Volunteer applications are reviewed by the desk and do not create employment unless separately agreed in writing.</p>
                            <h2>Intellectual property</h2>
                            <p>Site content, branding, and original programme notes belong to Stand For Humanity Foundation unless otherwise stated. You may share links to public pages. You may not republish full articles as your own work without permission.</p>
                            <h2>Limitation of liability</h2>
                            <p>We work carefully, but the site is provided as available. To the fullest extent permitted by law, we are not liable for losses arising from reliance on general programme information or from temporary site interruptions.</p>
                            <h2>Privacy</h2>
                            <p>Personal data is handled under our <a href="{{ route('privacy') }}">Privacy Policy</a>, including cookies and advertising partners.</p>
                            <h2>Contact</h2>
                            <p class="news-note mb-0">Questions about these terms: info@standforhumanityfoundation.org · Owerri, Imo State, Nigeria. Last updated: 23 September 2026.</p>
                        </div>
                    </article>
                    <div class="mt-4">
                        <a class="btn btn-primary py-3 px-4" href="{{ route('contact') }}">Contact the desk</a>
                        <a class="btn btn-secondary py-3 px-4 ms-2" href="{{ route('privacy') }}">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('site.partials.footer')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('Charitize/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('Charitize/js/main.js') }}?v=impact-video"></script>
    <script src="{{ asset('Charitize/js/cookie-banner.js') }}" defer></script>
</body>
</html>
