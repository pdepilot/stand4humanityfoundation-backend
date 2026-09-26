<?php

namespace App\Http\Controllers;

use App\Actions\CreateVolunteerApplicationAction;
use App\Actions\StoreVolunteerPhotoAction;
use App\Enums\VolunteerSource;
use App\Http\Requests\StoreVolunteerApplicationRequest;
use Illuminate\Http\JsonResponse;

class VolunteerApplicationController extends Controller
{
    /**
     * Record an application from the public volunteer form.
     */
    public function store(
        StoreVolunteerApplicationRequest $request,
        CreateVolunteerApplicationAction $createVolunteerApplication,
        StoreVolunteerPhotoAction $storeVolunteerPhoto,
    ): JsonResponse {
        $details = $request->applicationDetails();
        $details['photo'] = $storeVolunteerPhoto->store($request->uploadedPhoto());

        $application = $createVolunteerApplication->handle(
            $details,
            VolunteerSource::Public,
        );

        return response()->json([
            'ok' => true,
            'volunteer' => $application->toPublicArray(),
        ], 201);
    }
}
