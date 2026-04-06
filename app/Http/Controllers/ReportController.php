<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function create(User $user)
    {
        $currentUser = Auth::user();
        $reportedUser = $user;
        
        // Prevent users from reporting themselves
        if ($currentUser->id == $reportedUser->id) {
            return redirect()->back()->with('error', 'You cannot report yourself.');
        }
        
        // Calculate age
        $reportedUser->age = $reportedUser->dob ? \Carbon\Carbon::parse($reportedUser->dob)->age : null;
        
        return view('pages.report', [
            'reportedUser' => $reportedUser,
        ]);
    }

    public function store(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $reportedUser = $user;
        
        // Prevent users from reporting themselves
        if ($currentUser->id == $reportedUser->id) {
            return response()->json(['error' => 'You cannot report yourself.'], 403);
        }
        
        $request->validate([
            'reason' => 'required|string|in:spam_scam,harassment,inappropriate_photos,underage,other',
            'details' => 'nullable|string|max:500',
            'block_user' => 'boolean',
        ]);
        
        // Check if user already reported this user
        $existingReport = Report::where('reporter_id', $currentUser->id)
            ->where('reported_user_id', $reportedUser->id)
            ->where('status', 'pending')
            ->first();
        
        if ($existingReport) {
            return response()->json(['error' => 'You have already reported this user.'], 400);
        }
        
        $report = Report::create([
            'reporter_id' => $currentUser->id,
            'reported_user_id' => $reportedUser->id,
            'reason' => $request->reason,
            'details' => $request->details,
            'block_user' => $request->has('block_user') && $request->block_user,
            'status' => 'pending',
        ]);
        
        // If block_user is true, create a block record (you may want to create a blocks table)
        if ($report->block_user) {
            // You can implement blocking functionality here
            // For now, we'll just store it in the report
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. Our team will review it within 24 hours.',
        ]);
    }
}
