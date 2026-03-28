<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Award;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // GET /api/reports/dashboard  (Admin only)
    public function dashboard(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return response()->json([
            'total_users'        => User::count(),
            'total_students'     => User::where('role', 'Student')->count(),
            'total_scholarships' => Scholarship::count(),
            'active_scholarships'=> Scholarship::where('status', 'active')->count(),
            'total_applications' => Application::count(),
            'applications_by_status' => Application::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
            'total_awards'       => Award::count(),
            'total_amount_granted' => Award::sum('amount_granted'),
        ]);
    }

    // GET /api/reports/applications  (Admin only)
    public function applicationReport(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $applications = Application::with(['user', 'scholarship'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->scholarship_id, fn($q) => $q->where('scholarship_id', $request->scholarship_id))
            ->latest()
            ->paginate(20);

        $summary = Application::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json([
            'summary'      => $summary,
            'applications' => $applications,
        ]);
    }

    // GET /api/reports/awards  (Admin only)
    public function awardReport(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $awards = Award::with(['user', 'scholarship'])
            ->when($request->scholarship_id, fn($q) => $q->where('scholarship_id', $request->scholarship_id))
            ->orderByDesc('award_date')
            ->paginate(20);

        return response()->json([
            'total_awards'         => Award::count(),
            'total_amount_granted' => Award::sum('amount_granted'),
            'awards'               => $awards,
        ]);
    }
}
