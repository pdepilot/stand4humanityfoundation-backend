<div class="row g-4">
    @foreach ($teamMembers as $teamMember)
        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="{{ ['0.1s', '0.3s', '0.5s'][$loop->index % 3] }}">
            <div class="team-item d-flex h-100 p-4">
                <div class="team-detail pe-4">
                    <img class="img-fluid mb-4" src="{{ $teamMember->photoUrl() }}" alt="{{ $teamMember->name }}" loading="lazy">
                    <h3>{{ $teamMember->name }}</h3>
                    <span>{{ $teamMember->role }}</span>
                </div>
                @if ($teamMember->socialLinks() !== [])
                    <div class="team-social bg-light d-flex flex-column justify-content-center flex-shrink-0 p-4">
                        @foreach ($teamMember->socialLinks() as $link)
                            <a class="btn btn-square btn-primary my-2" href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $teamMember->name }} on {{ $link['label'] }}"><i class="{{ $link['icon'] }}"></i></a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
