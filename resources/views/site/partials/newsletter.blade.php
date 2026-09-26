{{--
    @var string $heading
    @var string $copy
--}}
@php
    $heading = $heading ?? 'Subscribe to Our Newsletter';
    $copy = $copy ?? 'Updates on our programmes, volunteer calls, and ways to stand with people in need — no spam.';
@endphp
<div class="container-fluid bg-primary py-5 mt-5 wow fadeIn newsletter-capture" data-wow-delay="0.1s">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center wow fadeIn" data-wow-delay="0.5s">
                <h2 class="display-6 mb-4 text-white">{{ $heading }}</h2>
                <div class="newsletter-alert alert d-none text-start mb-3" role="status"></div>
                <form class="newsletter-capture-form" method="post" action="{{ route('newsletter.api.store') }}" novalidate>
                    @csrf
                    <div class="position-relative w-100 mb-2">
                        <input class="form-control border-0 w-100 ps-4 pe-5" type="email" name="email"
                            placeholder="Enter Your Email" style="height: 60px;" aria-label="Email for newsletter" required maxlength="120" autocomplete="email">
                        <button type="submit" class="btn btn-lg-square shadow-none position-absolute top-0 end-0 mt-2 me-2" aria-label="Subscribe to newsletter">
                            <i class="fa fa-paper-plane text-primary fs-4" aria-hidden="true"></i>
                        </button>
                    </div>
                    {{-- Honeypot after the real field so browsers do not autofill it. --}}
                    <div class="newsletter-honeypot" aria-hidden="true">
                        <label>Company website</label>
                        <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                    </div>
                </form>
                <p class="mb-0 text-white">{{ $copy }}</p>
            </div>
        </div>
    </div>
</div>
@once('newsletter-capture-assets')
    <style>
        .newsletter-honeypot {
            position: absolute !important;
            left: -9999px !important;
            width: 1px !important;
            height: 1px !important;
            overflow: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }
        .newsletter-capture .newsletter-alert {
            background: #fff;
            color: #212529;
            border: 0;
        }
        .newsletter-capture .newsletter-alert.alert-danger {
            background: #f8d7da;
            color: #842029;
        }
        .newsletter-capture .newsletter-alert.alert-success {
            background: #d1e7dd;
            color: #0f5132;
        }
    </style>
    <script src="{{ asset('Charitize/js/newsletter-capture.js') }}?v=fix-2" defer></script>
@endonce
