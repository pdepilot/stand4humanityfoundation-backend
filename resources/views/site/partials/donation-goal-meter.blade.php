<div class="donation-progress d-flex flex-column flex-shrink-0 text-center me-4" data-donation-cause="{{ $progress->cause->value }}">
    <h6 class="mb-0">Raised</h6>
    <span class="mb-2 js-donation-raised">&#8358;{{ $progress->formattedRaised() }}</span>
    <div class="progress d-flex align-items-end w-100 h-100 mb-2">
        <div class="progress-bar w-100 bg-secondary js-donation-bar" role="progressbar" aria-valuenow="{{ $progress->percent }}" aria-valuemin="0" aria-valuemax="100">
            <span class="fs-4 js-donation-percent">{{ $progress->percent }}%</span>
        </div>
    </div>
    <h6 class="mb-0">Goal</h6>
    <span class="js-donation-goal">&#8358;{{ $progress->formattedGoal() }}</span>
</div>
