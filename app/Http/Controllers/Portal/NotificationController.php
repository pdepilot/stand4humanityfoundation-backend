<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Return the administrator notification feed.
     */
    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()?->isAdministrator() ?? false, 403);

        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn (DatabaseNotification $notification): array => [
                'id' => $notification->id,
                'type' => $notification->data['type'] ?? 'activity',
                'title' => $notification->data['title'] ?? 'Desk alert',
                'body' => $notification->data['body'] ?? '',
                'url' => $notification->data['url'] ?? route('portal.dashboard'),
                'reference' => $notification->data['reference'] ?? '',
                'read' => $notification->read_at !== null,
                'createdAt' => $notification->created_at?->toIso8601String(),
            ])
            ->values();

        return response()->json([
            'ok' => true,
            'unread' => $request->user()->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark one notification as read.
     */
    public function update(Request $request, string $notification): JsonResponse
    {
        abort_unless($request->user()?->isAdministrator() ?? false, 403);

        $record = $request->user()->notifications()->whereKey($notification)->firstOrFail();
        $record->markAsRead();

        return response()->json([
            'ok' => true,
            'unread' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark every notification as read.
     */
    public function destroy(Request $request): JsonResponse
    {
        abort_unless($request->user()?->isAdministrator() ?? false, 403);

        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'ok' => true,
            'unread' => 0,
        ]);
    }
}
