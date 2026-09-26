<?php

namespace App\Http\Controllers;

use App\Actions\CreateContactMessageAction;
use App\Enums\ContactSource;
use App\Http\Requests\StoreContactMessageRequest;
use Illuminate\Http\JsonResponse;

class ContactMessageController extends Controller
{
    /**
     * Record a message from the public contact form.
     */
    public function store(StoreContactMessageRequest $request, CreateContactMessageAction $createContactMessage): JsonResponse
    {
        $message = $createContactMessage->handle($request->contactDetails(), ContactSource::Public);

        return response()->json([
            'ok' => true,
            'contact' => $message->toPublicArray(),
        ], 201);
    }
}
