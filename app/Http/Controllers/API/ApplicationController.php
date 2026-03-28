<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ApplicationController extends Controller
{
    // GET /api/applications
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $applications = Application::with(['user', 'scholarship'])->paginate(15);
        } else {
            $applications = Application::with('scholarship')
                ->where('user_id', $user->user_id)
                ->paginate(15);
        }

        return response()->json($applications);
    }

    // POST /api/applications
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholarship_id' => 'required|exists:scholarships,scholarship_id',
        ]);

        $duplicate = Application::where('user_id', $request->user()->user_id)
            ->where('scholarship_id', $validated['scholarship_id'])
            ->whereNotIn('status', ['rejected'])
            ->first();

        if ($duplicate) {
            return response()->json(['message' => 'You already have an active application for this scholarship.'], 422);
        }

        $application = Application::create([
            'user_id'        => $request->user()->user_id,
            'scholarship_id' => $validated['scholarship_id'],
            'status'         => 'draft',
        ]);

        return response()->json([
            'message'     => 'Application created.',
            'application' => $application->load('scholarship'),
        ], 201);
    }

    // GET /api/applications/{application}
    public function show(Request $request, Application $application)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $application->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json($application->load(['user', 'scholarship', 'documents', 'reviewer']));
    }

    // PUT /api/applications/{application}
    public function update(Request $request, Application $application)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $application->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if ($application->status !== 'draft') {
            return response()->json(['message' => 'Only draft applications can be edited.'], 422);
        }

        $validated = $request->validate([
            'remarks' => 'nullable|string',
        ]);

        $application->update($validated);

        return response()->json(['message' => 'Application updated.', 'application' => $application]);
    }

    // GET /api/applications/{application}/status
    public function status(Request $request, Application $application)
    {
        $user = $request->user();

        if (! $user->isAdmin() && $application->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json([
            'id'           => $application->id,
            'status'       => $application->status,
            'submitted_at' => $application->submitted_at,
            'reviewed_at'  => $application->reviewed_at,
        ]);
    }

    // POST /api/applications/{application}/submit
    public function submit(Request $request, Application $application)
    {
        $user = $request->user();

        if ($application->user_id !== $user->user_id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if ($application->status !== 'draft') {
            return response()->json(['message' => 'Only draft applications can be submitted.'], 422);
        }

        $application->update([
            'status'       => 'submitted',
            'submitted_at' => Carbon::now(),
        ]);

        return response()->json(['message' => 'Application submitted.', 'application' => $application]);
    }

    // POST /api/applications/{application}/review  (Admin only)
    public function review(Request $request, Application $application)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if ($application->status !== 'submitted') {
            return response()->json(['message' => 'Only submitted applications can be reviewed.'], 422);
        }

        $application->update([
            'status'      => 'under_review',
            'reviewed_at' => Carbon::now(),
            'reviewed_by' => $request->user()->user_id,
        ]);

        return response()->json(['message' => 'Application is now under review.', 'application' => $application]);
    }

    // POST /api/applications/{application}/approve  (Admin only)
    public function approve(Request $request, Application $application)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if (! in_array($application->status, ['submitted', 'under_review'])) {
            return response()->json(['message' => 'Application cannot be approved at this stage.'], 422);
        }

        $request->validate(['remarks' => 'nullable|string']);

        $application->update([
            'status'      => 'approved',
            'remarks'     => $request->remarks,
            'reviewed_at' => Carbon::now(),
            'reviewed_by' => $request->user()->user_id,
        ]);

        return response()->json(['message' => 'Application approved.', 'application' => $application]);
    }

    // POST /api/applications/{application}/reject  (Admin only)
    public function reject(Request $request, Application $application)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if (! in_array($application->status, ['submitted', 'under_review'])) {
            return response()->json(['message' => 'Application cannot be rejected at this stage.'], 422);
        }

        $request->validate(['remarks' => 'nullable|string']);

        $application->update([
            'status'      => 'rejected',
            'remarks'     => $request->remarks,
            'reviewed_at' => Carbon::now(),
            'reviewed_by' => $request->user()->user_id,
        ]);

        return response()->json(['message' => 'Application rejected.', 'application' => $application]);
    }
}
