<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlockController extends Controller
{
    /**
     * Block a user
     */
    public function block(User $user)
    {
        $currentUser = Auth::user();

        // Prevent users from blocking themselves
        if ($currentUser->id == $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot block yourself.',
            ], 400);
        }

        // Check if already blocked
        $alreadyBlocked = DB::table('user_blocks')
            ->where('blocker_id', $currentUser->id)
            ->where('blocked_id', $user->id)
            ->exists();

        if ($alreadyBlocked) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is already blocked.',
            ], 400);
        }

        // Create block record
        DB::table('user_blocks')->insert([
            'blocker_id' => $currentUser->id,
            'blocked_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User has been blocked successfully.',
            'data' => [
                'blocked_user_id' => $user->id,
                'is_blocked' => true,
            ],
        ]);
    }

    /**
     * Unblock a user
     */
    public function unblock(User $user)
    {
        $currentUser = Auth::user();

        // Remove block record
        $deleted = DB::table('user_blocks')
            ->where('blocker_id', $currentUser->id)
            ->where('blocked_id', $user->id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is not blocked.',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User has been unblocked successfully.',
            'data' => [
                'blocked_user_id' => $user->id,
                'is_blocked' => false,
            ],
        ]);
    }

    /**
     * Check if a user is blocked
     */
    public function check(User $user)
    {
        $currentUser = Auth::user();

        $isBlocked = DB::table('user_blocks')
            ->where('blocker_id', $currentUser->id)
            ->where('blocked_id', $user->id)
            ->exists();

        return response()->json([
            'status' => 'success',
            'data' => [
                'is_blocked' => $isBlocked,
            ],
        ]);
    }

    /**
     * Get list of blocked users
     */
    public function index()
    {
        $currentUser = Auth::user();

        $blockedIds = DB::table('user_blocks')
            ->where('blocker_id', $currentUser->id)
            ->pluck('blocked_id')
            ->toArray();

        $blockedUsers = User::whereIn('id', $blockedIds)
            ->select('id', 'full_name', 'profile_image', 'email')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $blockedUsers,
        ]);
    }
}
