<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SmsNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotificationController extends Controller
{
    // GET /api/notifications
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $notifications = SmsNotification::with('user')->latest()->paginate(20);
        } else {
            $notifications = SmsNotification::where('user_id', $user->user_id)->latest()->paginate(20);
        }

        return response()->json($notifications);
    }

    // GET /api/notifications/{notification}
    public function show(Request $request, SmsNotification $notification)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $notification->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json($notification);
    }

    // POST /api/notifications/send  (Admin only)
    public function send(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'type'    => 'sometimes|string|max:50',
        ]);

        $notification = SmsNotification::create($validated);

        return response()->json([
            'message'      => 'Notification sent.',
            'notification' => $notification,
        ], 201);
    }

    // PATCH /api/notifications/{notification}/read
    public function markRead(Request $request, SmsNotification $notification)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $notification->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $notification->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Notification marked as read.']);
    }

    // PATCH /api/notifications/read-all
    public function markAllRead(Request $request)
    {
        SmsNotification::where('user_id', $request->user()->user_id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => Carbon::now()]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }

    // DELETE /api/notifications/{notification}
    public function destroy(Request $request, SmsNotification $notification)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $notification->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted.']);
    }
}
