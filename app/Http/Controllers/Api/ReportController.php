<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function create(User $user)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'reported_user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                ],
                'reasons' => [
                    'spam_scam' => 'Spam/Scam',
                    'harassment' => 'Harassment',
                    'inappropriate_photos' => 'Inappropriate Photos',
                    'underage' => 'Underage',
                    'other' => 'Other',
                ],
            ],
        ]);
    }

    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => 'required|in:spam_scam,harassment,inappropriate_photos,underage,other',
            'details' => 'nullable|string|max:1000',
            'block_user' => 'nullable|boolean',
        ]);

        Report::create([
            'reporter_id' => Auth::id(),
            'reported_user_id' => $user->id,
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
            'block_user' => $validated['block_user'] ?? false,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Report submitted successfully',
        ]);
    }
}

