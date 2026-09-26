<?php

namespace App\Http\Controllers;

use App\Actions\CreateNewsletterSubscriberAction;
use App\Enums\NewsletterSource;
use App\Http\Requests\StoreNewsletterSubscriberRequest;
use Illuminate\Http\JsonResponse;

class NewsletterSubscriberController extends Controller
{
    /**
     * Record a public newsletter subscription.
     */
    public function store(
        StoreNewsletterSubscriberRequest $request,
        CreateNewsletterSubscriberAction $createNewsletterSubscriber,
    ): JsonResponse {
        $result = $createNewsletterSubscriber->handle(
            $request->subscriberDetails(),
            NewsletterSource::Public,
            $request->ip(),
        );

        return response()->json([
            'ok' => true,
            'subscriber' => $result['subscriber']->toPublicArray(),
            'created' => $result['created'],
        ], $result['created'] ? 201 : 200);
    }
}
