<?php

namespace App\Http\Controllers;

use App\Actions\StoreTestimonyPhotoAction;
use App\Enums\TestimonyStatus;
use App\Http\Requests\StoreTestimonyRequest;
use App\Models\Testimony;
use Illuminate\Http\JsonResponse;

class TestimonyController extends Controller
{
    /**
     * Record a public testimony for administrator review.
     */
    public function store(StoreTestimonyRequest $request, StoreTestimonyPhotoAction $storePhoto): JsonResponse
    {
        $details = $request->testimonyDetails();
        $details['status'] = TestimonyStatus::Pending;
        $photo = $request->uploadedPhoto();

        if ($photo !== null) {
            $details['photo'] = $storePhoto->store($photo);
        }

        Testimony::query()->create($details);

        return response()->json([
            'ok' => true,
            'message' => 'Thank you. Your testimony is waiting for review.',
        ], 201);
    }
}
