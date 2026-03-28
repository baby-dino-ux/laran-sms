<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\SmsNotification;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    // GET /api/awards
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $awards = Award::with(['user', 'scholarship'])->paginate(15);
        } else {
            $awards = Award::with('scholarship')
                ->where('user_id', $user->user_id)
                ->paginate(15);
        }

        return response()->json($awards);
    }

    // POST /api/awards  (Admin only)
    public function store(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $validated = $request->validate([
            'user_id'        => 'required|exists:users,user_id',
            'scholarship_id' => 'required|exists:scholarships,scholarship_id',
            'application_id' => 'nullable|exists:applications,id',
            'amount_granted' => 'required|numeric|min:0',
            'award_date'     => 'required|date',
            'notes'          => 'nullable|string',
        ]);

        $award = Award::create($validated);

        return response()->json([
            'message' => 'Award granted.',
            'award'   => $award->load(['user', 'scholarship']),
        ], 201);
    }

    // GET /api/awards/history
    public function history(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $awards = Award::with(['user', 'scholarship'])->orderByDesc('award_date')->get();
        } else {
            $awards = Award::with('scholarship')
                ->where('user_id', $user->user_id)
                ->orderByDesc('award_date')
                ->get();
        }

        return response()->json($awards);
    }

    // GET /api/awards/{award}
    public function show(Request $request, Award $award)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $award->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json($award->load(['user', 'scholarship', 'application']));
    }

    // POST /api/awards/{award}/notify  (Admin only)
    public function sendNotification(Request $request, Award $award)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        SmsNotification::create([
            'user_id' => $award->user_id,
            'title'   => 'Scholarship Award Notification',
            'message' => 'Congratulations! You have been awarded the ' . $award->scholarship->scholarship_name
                       . ' scholarship with an amount of ₱' . number_format($award->amount_granted, 2) . '.',
            'type'    => 'award',
        ]);

        $award->update(['notification_sent' => true]);

        return response()->json(['message' => 'Notification sent to the scholar.']);
    }
}
