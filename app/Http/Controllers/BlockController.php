<?php

namespace App\Http\Controllers;

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
            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You cannot block yourself.',
                ], 400);
            }
            return redirect()->back()->with('error', 'You cannot block yourself.');
        }

        // Check if already blocked
        $alreadyBlocked = DB::table('user_blocks')
            ->where('blocker_id', $currentUser->id)
            ->where('blocked_id', $user->id)
            ->exists();

        if ($alreadyBlocked) {
            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is already blocked.',
                ], 400);
            }
            return redirect()->back()->with('info', 'User is already blocked.');
        }

        // Create block record
        DB::table('user_blocks')->insert([
            'blocker_id' => $currentUser->id,
            'blocked_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'User has been blocked successfully.',
            ]);
        }

        return redirect()->back()->with('status', 'User has been blocked successfully.');
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
            if (request()->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not blocked.',
                ], 400);
            }
            return redirect()->back()->with('error', 'User is not blocked.');
        }

        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'User has been unblocked successfully.',
            ]);
        }

        return redirect()->back()->with('status', 'User has been unblocked successfully.');
    }

    /**
     * Check if a user is blocked
     */
    public static function isBlocked($blockerId, $blockedId): bool
    {
        return DB::table('user_blocks')
            ->where('blocker_id', $blockerId)
            ->where('blocked_id', $blockedId)
            ->exists();
    }

    /**
     * Get blocked user IDs for a user
     */
    public static function getBlockedIds($userId): array
    {
        return DB::table('user_blocks')
            ->where('blocker_id', $userId)
            ->pluck('blocked_id')
            ->toArray();
    }
}
