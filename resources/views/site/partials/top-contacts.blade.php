<div class="top-bar-contacts">
    <div class="top-bar-contact">
        <div class="flex-shrink-0 btn-square bg-primary">
            <i class="fa fa-phone-alt text-dark"></i>
        </div>
        <div class="ms-2">
            <h6 class="text-primary mb-0">Call Us</h6>
            <span class="text-white">{{ $siteProfile->phone }}</span>
        </div>
    </div>
    <div class="top-bar-contact">
        <div class="flex-shrink-0 btn-square bg-primary">
            <i class="fa fa-map-marker-alt text-dark"></i>
        </div>
        <div class="ms-2">
            <h6 class="text-primary mb-0">Address</h6>
            <span class="text-white">{{ $siteProfile->address }}</span>
        </div>
    </div>
    <div class="top-bar-contact">
        <div class="flex-shrink-0 btn-square bg-primary">
            <i class="fa fa-envelope-open text-dark"></i>
        </div>
        <div class="ms-2">
            <h6 class="text-primary mb-0">Mail Us</h6>
            <span class="text-white">{{ $siteProfile->email }}</span>
        </div>
    </div>
</div>
