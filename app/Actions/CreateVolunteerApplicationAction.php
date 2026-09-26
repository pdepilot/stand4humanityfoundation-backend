<?php

namespace App\Actions;

use App\Enums\VolunteerSource;
use App\Enums\VolunteerStatus;
use App\Events\FrontendActivityRecorded;
use App\Models\VolunteerApplication;
use Illuminate\Support\Str;

class CreateVolunteerApplicationAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data, VolunteerSource $source = VolunteerSource::Public): VolunteerApplication
    {
        $application = VolunteerApplication::query()->create([
            'reference' => 'vol-'.Str::lower(bin2hex(random_bytes(6))),
            'status' => VolunteerStatus::Pending,
            'source' => $source,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'city' => $data['city'],
            'interest' => $data['interest'],
            'availability' => $data['availability'],
            'experience' => $data['experience'] ?? null,
            'motivation' => $data['motivation'],
            'photo' => $data['photo'],
        ]);

        if ($source === VolunteerSource::Public) {
            FrontendActivityRecorded::dispatch(
                'volunteer',
                'Volunteer application received',
                $application->name.' · '.$application->interest->label(),
                route('portal.volunteers.show', $application, false),
                $application->reference,
            );
        }

        return $application;
    }
}
