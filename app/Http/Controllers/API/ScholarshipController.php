<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    // GET /api/scholarships
    public function index()
    {
        $scholarships = Scholarship::with('creator')->paginate(15);
        return response()->json($scholarships);
    }

    // GET /api/scholarships/{scholarship}
    public function show(Scholarship $scholarship)
    {
        $scholarship->load('creator', 'applications');
        return response()->json($scholarship);
    }

    // POST /api/scholarships  (Admin only)
    public function store(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $validated = $request->validate([
            'scholarship_name'    => 'required|string|max:255',
            'description'         => 'nullable|string',
            'amount'              => 'nullable|numeric|min:0',
            'slots'               => 'nullable|integer|min:0',
            'deadline'            => 'nullable|date',
            'status'              => 'sometimes|in:active,inactive,closed',
            'eligibility_criteria'=> 'nullable|array',
        ]);

        $validated['created_by'] = $request->user()->user_id;

        $scholarship = Scholarship::create($validated);

        return response()->json([
            'message'     => 'Scholarship created.',
            'scholarship' => $scholarship,
        ], 201);
    }

    // PUT /api/scholarships/{scholarship}  (Admin only)
    public function update(Request $request, Scholarship $scholarship)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $validated = $request->validate([
            'scholarship_name'    => 'sometimes|string|max:255',
            'description'         => 'nullable|string',
            'amount'              => 'nullable|numeric|min:0',
            'slots'               => 'nullable|integer|min:0',
            'deadline'            => 'nullable|date',
            'status'              => 'sometimes|in:active,inactive,closed',
            'eligibility_criteria'=> 'nullable|array',
        ]);

        $scholarship->update($validated);

        return response()->json([
            'message'     => 'Scholarship updated.',
            'scholarship' => $scholarship,
        ]);
    }

    // DELETE /api/scholarships/{scholarship}  (Admin only)
    public function destroy(Request $request, Scholarship $scholarship)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $scholarship->delete();

        return response()->json(['message' => 'Scholarship deleted.']);
    }

    // PUT /api/scholarships/{scholarship}/eligibility  (Admin only)
    public function setEligibility(Request $request, Scholarship $scholarship)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $request->validate([
            'eligibility_criteria' => 'required|array',
        ]);

        $scholarship->update([
            'eligibility_criteria' => $request->eligibility_criteria,
        ]);

        return response()->json([
            'message'     => 'Eligibility criteria updated.',
            'scholarship' => $scholarship,
        ]);
    }
}
