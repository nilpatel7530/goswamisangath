<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\CreatesNotifications;

class InterestController extends Controller
{
    use CreatesNotifications;

    public function send(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot send interest to yourself',
            ], 400);
        }

        // Check membership limits
        $activeMembership = $currentUser->activeMembership();
        
        if (!$activeMembership) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active membership found. Please subscribe to a plan.',
            ], 403);
        }

        $pivot = $activeMembership->pivot;

        // Check expiry date (if set)
        if ($pivot->expires_at && \Carbon\Carbon::parse($pivot->expires_at)->isPast()) {
            // Deactivate membership if expired
            $currentUser->memberships()->updateExistingPivot($activeMembership->id, ['is_active' => 0]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Your membership has expired. Please renew to continue.',
            ], 403);
        }

        // Check interest limit
        if ($pivot->interest_limit > 0 && $pivot->interests_used >= $pivot->interest_limit) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have reached your interest limit (' . $pivot->interest_limit . '). Please upgrade your plan.',
            ], 403);
        }

        // Check if interest already exists
        $existingInterest = DB::table('user_interests')
            ->where(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $user->id);
            })
            ->orWhere(function($query) use ($currentUser, $user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $currentUser->id);
            })
            ->first();

        if ($existingInterest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Interest already exists',
            ], 400);
        }

        $interestId = DB::table('user_interests')->insertGetId([
            'sender_id' => $currentUser->id,
            'receiver_id' => $user->id,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Increment interests_used
        $currentUser->memberships()->updateExistingPivot($activeMembership->id, [
            'interests_used' => $pivot->interests_used + 1,
            'updated_at' => now(),
        ]);

        // Create notification
        $this->createNotification(
            $user, // User object (recipient)
            'interest', // type
            $currentUser->full_name . ' sent you an interest', // message
            $currentUser, // related user (sender)
            'heart', // icon
            'red' // icon color
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Interest sent successfully',
            'data' => [
                'interest_id' => $interestId,
            ],
        ]);
    }

    public function accept($id)
    {
        $user = Auth::user();

        $interest = DB::table('user_interests')
            ->where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$interest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Interest request not found',
            ], 404);
        }

        DB::table('user_interests')
            ->where('id', $id)
            ->update(['status' => 'accepted', 'updated_at' => now()]);

        $sender = User::find($interest->sender_id);

        // Create notification
        $this->createNotification(
            $sender, // User object (recipient - the person who sent the interest)
            'interest_accepted', // type
            $user->full_name . ' accepted your interest', // message
            $user, // related user (the person who accepted)
            'heart', // icon
            'green' // icon color
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Interest accepted successfully',
            'data' => [
                'connection_established' => true,
                'sender' => [
                    'id' => $sender->id,
                    'full_name' => $sender->full_name,
                    'profile_image' => $sender->profile_image ? asset('storage/' . $sender->profile_image) : null,
                ],
            ],
        ]);
    }

    public function decline($id)
    {
        $user = Auth::user();

        $interest = DB::table('user_interests')
            ->where('id', $id)
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$interest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Interest request not found',
            ], 404);
        }

        DB::table('user_interests')
            ->where('id', $id)
            ->update(['status' => 'declined', 'updated_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Interest declined successfully',
        ]);
    }

    public function requests(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'incoming'); // incoming or sent

        if ($type === 'incoming') {
            return $this->incoming($request);
        } else {
            return $this->sent($request);
        }
    }

    public function incoming(Request $request)
    {
        $user = Auth::user();

        $interests = DB::table('user_interests')
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $requests = $interests->map(function($interest) {
            $sender = User::find($interest->sender_id);
            return [
                'id' => $interest->id,
                'sender' => [
                    'id' => $sender->id,
                    'full_name' => $sender->full_name,
                    'profile_image' => $sender->profile_image ? asset('storage/' . $sender->profile_image) : null,
                    'age' => $sender->dob ? \Carbon\Carbon::parse($sender->dob)->age : null,
                    'city' => $sender->city,
                    'occupation' => $sender->occupation,
                ],
                'status' => $interest->status,
                'created_at' => $interest->created_at,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $requests,
        ]);
    }

    public function sent(Request $request)
    {
        $user = Auth::user();

        $interests = DB::table('user_interests')
            ->where('sender_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $requests = $interests->map(function($interest) {
            $receiver = User::find($interest->receiver_id);
            return [
                'id' => $interest->id,
                'receiver' => [
                    'id' => $receiver->id,
                    'full_name' => $receiver->full_name,
                    'profile_image' => $receiver->profile_image ? asset('storage/' . $receiver->profile_image) : null,
                    'age' => $receiver->dob ? \Carbon\Carbon::parse($receiver->dob)->age : null,
                    'city' => $receiver->city,
                    'occupation' => $receiver->occupation,
                ],
                'status' => $interest->status,
                'created_at' => $interest->created_at,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $requests,
        ]);
    }

    public function toggleShortlist(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot shortlist yourself',
            ], 400);
        }

        $exists = DB::table('user_shortlists')
            ->where('user_id', $currentUser->id)
            ->where('shortlisted_user_id', $user->id)
            ->exists();

        if ($exists) {
            DB::table('user_shortlists')
                ->where('user_id', $currentUser->id)
                ->where('shortlisted_user_id', $user->id)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Removed from shortlist',
                'data' => ['is_shortlisted' => false],
            ]);
        } else {
            DB::table('user_shortlists')->insert([
                'user_id' => $currentUser->id,
                'shortlisted_user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Added to shortlist',
                'data' => ['is_shortlisted' => true],
            ]);
        }
    }

    public function shortlist(Request $request)
    {
        $user = Auth::user();

        $shortlistedIds = DB::table('user_shortlists')
            ->where('user_id', $user->id)
            ->pluck('shortlisted_user_id');

        $shortlistedUsers = User::whereIn('id', $shortlistedIds)->get();

        $formatted = $shortlistedUsers->map(function($shortlisted) {
            return [
                'id' => $shortlisted->id,
                'full_name' => $shortlisted->full_name,
                'profile_image' => $shortlisted->profile_image ? asset('storage/' . $shortlisted->profile_image) : null,
                'age' => $shortlisted->dob ? \Carbon\Carbon::parse($shortlisted->dob)->age : null,
                'city' => $shortlisted->city,
                'state' => $shortlisted->state,
                'occupation' => $shortlisted->occupation,
                'highest_education' => $shortlisted->highest_education,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted,
        ]);
    }
}

