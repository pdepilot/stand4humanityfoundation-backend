@foreach ($siteProfile->headerSocialLinks() as $link)
    <a class="btn btn-square btn-dark ms-2" href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $link['label'] }}"><i class="{{ $link['icon'] }}"></i></a>
@endforeach
