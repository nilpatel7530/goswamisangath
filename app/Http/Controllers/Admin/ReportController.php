<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportedUser']);

        // Search functionality - search across multiple fields
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                  ->orWhere('reason', 'like', "%{$searchTerm}%")
                  ->orWhere('details', 'like', "%{$searchTerm}%")
                  ->orWhereHas('reporter', function ($q) use ($searchTerm) {
                      $q->where('full_name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('reportedUser', function ($q) use ($searchTerm) {
                      $q->where('full_name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by reason
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        // Filter by block requested
        if ($request->filled('block_requested')) {
            $query->where('block_user', $request->block_requested == 'yes' ? 1 : 0);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort_by to prevent SQL injection
        $allowedSortColumns = ['id', 'status', 'reason', 'created_at', 'updated_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort_order
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';
        
        $query->orderBy($sortBy, $sortOrder);

        $reports = $query->paginate(20)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report)
    {
        $report->load(['reporter', 'reportedUser']);
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Update the status of a report.
     */
    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,dismissed',
        ]);

        $report->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.reports.index')
            ->with('success', 'Report status updated successfully.');
    }

    /**
     * Update multiple reports status.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id',
            'status' => 'required|in:pending,reviewed,resolved,dismissed',
        ]);

        Report::whereIn('id', $request->report_ids)
            ->update(['status' => $request->status]);

        return redirect()->route('admin.reports.index')
            ->with('success', count($request->report_ids) . ' report(s) status updated successfully.');
    }
}
