    <!-- Testimonial Start -->
    <div class="container-fluid py-5" id="testimonies">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-12 col-lg-4 col-xl-3 wow fadeIn" data-wow-delay="0.1s">
                    <div class="testimonial-title">
                        <h1 class="display-6 mb-4">What We Stand For.</h1>
                        <p class="fs-5 mb-4">Stories from people the foundation has stood with. A testimony appears here after the desk approves it.</p>
                        <button class="btn btn-primary py-3 px-4" type="button" id="giveTestimonyButton" aria-expanded="false" aria-controls="give-testimony">Give testimony</button>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8 col-xl-9">
                    @if ($testimonies->isNotEmpty())
                        <div class="owl-carousel testimonial-carousel wow fadeIn" data-wow-delay="0.3s">
                            @foreach ($testimonies as $testimony)
                                <div class="testimonial-item">
                                    <div class="row g-5 align-items-center">
                                        <div class="col-md-6">
                                            <div class="testimonial-img">
                                                <img class="img-fluid" src="{{ $testimony->photoUrl() }}" alt="{{ $testimony->name }}" loading="lazy">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="testimonial-text pb-5 pb-md-0">
                                                <div class="mb-2" aria-label="{{ $testimony->rating }} of 5">
                                                    @for ($star = 1; $star <= 5; $star++)
                                                        <i class="fa fa-star {{ $star <= $testimony->rating ? 'text-primary' : 'text-secondary' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="fs-5">{{ $testimony->body }}</p>
                                                <div class="d-flex align-items-center">
                                                    <div class="btn-lg-square bg-light text-secondary flex-shrink-0">
                                                        <i class="fa fa-quote-right fa-2x"></i>
                                                    </div>
                                                    <div class="ps-3">
                                                        <h5 class="mb-0">{{ $testimony->name }}</h5>
                                                        <span>{{ $testimony->role ?: 'Community' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="fs-5 mb-0">Approved testimonies will appear here.</p>
                    @endif
                </div>
            </div>

            <div id="give-testimony" class="d-none" hidden>
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-8">
                    <div class="bg-light p-4 p-md-5">
                        <h2 class="display-6 mb-3">Give testimony</h2>
                        <p class="mb-4">Tell us what the foundation meant to you. The desk reviews every testimony before it is shown.</p>
                        <div id="testimonyAlert" class="alert d-none" role="status"></div>
                        <form id="testimonyCaptureForm" method="post" action="{{ route('testimonies.api.store') }}" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="report-honeypot" aria-hidden="true">
                                <label for="testimonyWebsite">Website</label>
                                <input type="text" id="testimonyWebsite" name="website" tabindex="-1" autocomplete="off">
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="testimonyName" name="name" placeholder="Your name" required maxlength="80" autocomplete="name">
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" id="testimonyEmail" name="email" placeholder="Email, if you want a reply" maxlength="120" autocomplete="email">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="testimonyRole" name="role" placeholder="Parent, volunteer, neighbour" maxlength="80">
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" id="testimonyRating" name="rating" required>
                                        <option value="" selected disabled>Rating</option>
                                        <option value="5">5 — Strong</option>
                                        <option value="4">4</option>
                                        <option value="3">3</option>
                                        <option value="2">2</option>
                                        <option value="1">1</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" id="testimonyBody" name="body" placeholder="Your testimony" style="height: 160px" required minlength="20" maxlength="1000"></textarea>
                                </div>
                                <div class="col-12">
                                    <input type="file" class="form-control" id="testimonyPhoto" name="photo" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary py-3 px-4" type="submit" id="testimonySubmitBtn" data-original-text="Submit testimony">Submit testimony</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
