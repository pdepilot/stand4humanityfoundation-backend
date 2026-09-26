<div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="row g-5 py-5">
            <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Our Office</h4>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>{{ $siteProfile->address }}</p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>{{ $siteProfile->phone }}</p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i>{{ $siteProfile->email }}</p>
                @if ($siteProfile->socialLinks() !== [])
                    <div class="d-flex pt-3">
                        @foreach ($siteProfile->socialLinks() as $link)
                            <a class="btn btn-square btn-primary me-2" href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $link['label'] }}"><i class="{{ $link['icon'] }}"></i></a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Quick Links</h4>
                <a class="btn btn-link" href="{{ route('about') }}">About</a>
                <a class="btn btn-link" href="{{ route('contact') }}">Contact Us</a>
                <a class="btn btn-link" href="{{ route('news') }}">News</a>
                <a class="btn btn-link" href="{{ route('privacy') }}">Privacy Policy</a>
                <a class="btn btn-link" href="{{ route('terms') }}">Terms of Use</a>
                <a class="btn btn-link" href="{{ route('donate') }}">Donate</a>
                <a class="btn btn-link" href="{{ route('volunteer') }}">Volunteer</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Response Hours</h4>
                <p class="mb-1">{{ $siteProfile->weekday_label }}</p>
                <h6 class="text-light">{{ $siteProfile->weekday_hours }}</h6>
                <p class="mb-1">{{ $siteProfile->saturday_label }}</p>
                <h6 class="text-light">{{ $siteProfile->saturday_hours }}</h6>
                <p class="mb-1">{{ $siteProfile->extra_label }}</p>
                <h6 class="text-light">{{ $siteProfile->extra_hours }}</h6>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="text-light mb-4">Gallery</h4>
                <div class="row g-2">
                    @foreach ($siteProfile->gallery() as $image)
                        <div class="col-4">
                            <img class="img-fluid w-100" src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" loading="lazy">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="copyright pt-5">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="fw-semi-bold" href="{{ route('home') }}">Stand For Humanity Foundation</a>, All Right Reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    Designed By <span class="fw-semi-bold">ERIBS Tech</span>
                </div>
            </div>
        </div>
    </div>
</div>
