@if ($embedUrl = $siteProfile->impactVideoEmbedUrl())
    <button type="button" class="btn-play" data-bs-toggle="modal" data-src="{{ $embedUrl }}" data-bs-target="#videoModal" aria-label="Play the foundation video">
        <span></span>
    </button>
@endif
