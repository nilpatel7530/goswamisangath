<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get stats
        $stats = [
            'total_matches' => $this->getTotalMatches($user),
            'pending_requests' => $this->getPendingRequests($user),
            'unread_messages' => $this->getUnreadMessages($user),
            'unread_notifications' => $this->getUnreadNotifications($user),
        ];

        // Get recent activity
        $recentActivity = $this->getRecentActivity($user);

        // Get recommended matches (limit 5)
        $recommendedMatches = $this->getRecommendedMatches($user, 5);

        // Get active membership
        $activeMembership = $user->activeMembership();
        $membershipData = null;
        if ($activeMembership) {
            $membershipData = [
                'name' => $activeMembership->name,
                'price' => $activeMembership->price,
                'visits_allowed' => $activeMembership->pivot->visits_allowed,
                'visits_used' => $activeMembership->pivot->visits_used,
                'expires_at' => $activeMembership->pivot->expires_at,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'stats' => $stats,
                'recent_activity' => $recentActivity,
                'recommended_matches' => $recommendedMatches,
                'active_membership' => $membershipData,
            ],
        ]);
    }

    private function getTotalMatches($user)
    {
        // Count users of opposite gender (simplified)
        return DB::table('users')
            ->where('gender', '!=', $user->gender)
            ->where('id', '!=', $user->id)
            ->count();
    }

    private function getPendingRequests($user)
    {
        return DB::table('user_interests')
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->count();
    }

    private function getUnreadMessages($user)
    {
        return DB::table('messages')
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    private function getUnreadNotifications($user)
    {
        return DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    private function getRecentActivity($user)
    {
        $activities = [];

        // Recent interests received
        $recentInterests = DB::table('user_interests')
            ->where('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentInterests as $interest) {
            $sender = DB::table('users')->where('id', $interest->sender_id)->first();
            $activities[] = [
                'type' => 'interest_received',
                'message' => $sender->full_name . ' sent you an interest',
                'created_at' => $interest->created_at,
            ];
        }

        // Recent messages
        $recentMessages = DB::table('messages')
            ->where('receiver_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentMessages as $message) {
            $sender = DB::table('users')->where('id', $message->sender_id)->first();
            $activities[] = [
                'type' => 'message_received',
                'message' => 'New message from ' . $sender->full_name,
                'created_at' => $message->created_at,
            ];
        }

        // Sort by created_at and limit to 10
        usort($activities, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return array_slice($activities, 0, 10);
    }

    private function getRecommendedMatches($user, $limit = 5)
    {
        $matches = DB::table('users')
            ->where('gender', '!=', $user->gender)
            ->where('id', '!=', $user->id)
            ->select('id', 'full_name', 'profile_image', 'dob', 'city', 'state', 'occupation', 'highest_education')
            ->limit($limit)
            ->get();

        return $matches->map(function($match) {
            return [
                'id' => $match->id,
                'full_name' => $match->full_name,
                'profile_image' => $match->profile_image ? asset('storage/' . $match->profile_image) : null,
                'age' => $match->dob ? \Carbon\Carbon::parse($match->dob)->age : null,
                'location' => ($match->city ?? '') . ', ' . ($match->state ?? ''),
                'occupation' => $match->occupation,
                'education' => $match->highest_education,
            ];
        });
    }
}

