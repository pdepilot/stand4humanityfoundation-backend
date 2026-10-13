<div class="row g-4">
    @foreach ($teamMembers as $teamMember)
        <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="{{ ['0.1s', '0.3s', '0.5s'][$loop->index % 3] }}">
            <div class="team-item h-100 p-4">
                <div class="team-detail">
                    <img class="img-fluid mb-4" src="{{ $teamMember->photoUrl() }}" alt="{{ $teamMember->name }}" loading="lazy">
                    <h3>{{ $teamMember->name }}</h3>
                    <span class="team-position">{{ $teamMember->role }}</span>
                    @if ($teamMember->socialLinks() !== [])
                        <div class="team-social d-flex flex-wrap gap-2 mt-3">
                            @foreach ($teamMember->socialLinks() as $link)
                                <a class="btn btn-square btn-primary" href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $teamMember->name }} on {{ $link['label'] }}"><i class="{{ $link['icon'] }}"></i></a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
