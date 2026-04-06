<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('price')
            ->get();

        $formatted = $memberships->map(function($membership) {
            return [
                'id' => $membership->id,
                'name' => $membership->name,
                'price' => (float) $membership->price,
                'visits_allowed' => $membership->visits_allowed,
                'features' => $membership->features,
                'is_featured' => $membership->is_featured,
                'badge' => $membership->badge,
                'description' => $membership->description,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted,
        ]);
    }

    public function current()
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership();

        if (!$activeMembership) {
            return response()->json([
                'status' => 'success',
                'data' => null,
            ]);
        }

        $pivot = $activeMembership->pivot;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $activeMembership->id,
                'name' => $activeMembership->name,
                'price' => (float) $activeMembership->price,
                'visits_allowed' => $activeMembership->visits_allowed,
                'visits_used' => $pivot->visits_used,
                'purchased_at' => $pivot->purchased_at,
                'expires_at' => $pivot->expires_at,
                'days_remaining' => $pivot->expires_at ? now()->diffInDays(\Carbon\Carbon::parse($pivot->expires_at), false) : null,
            ],
        ]);
    }

    public function subscribe(Membership $membership)
    {
        $user = Auth::user();

        // If free plan, activate directly
        if ($membership->price <= 0) {
            // Deactivate existing memberships
            DB::table('user_memberships')
                ->where('user_id', $user->id)
                ->where('is_active', 1)
                ->update(['is_active' => 0]);

            // Create new membership
            DB::table('user_memberships')->insert([
                'user_id' => $user->id,
                'membership_id' => $membership->id,
                'is_active' => 1,
                'visits_used' => 0,
                'purchased_at' => now(),
                'expires_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully subscribed to ' . $membership->name . ' plan',
                'data' => [
                    'membership' => [
                        'id' => $membership->id,
                        'name' => $membership->name,
                        'expires_at' => null,
                    ],
                ],
            ]);
        }

        // For paid plans, return payment order creation endpoint
        return response()->json([
            'status' => 'payment_required',
            'message' => 'Payment required for this membership plan',
            'data' => [
                'payment_endpoint' => url('/api/v1/payment/create/' . $membership->id),
                'membership' => [
                    'id' => $membership->id,
                    'name' => $membership->name,
                    'price' => (float) $membership->price,
                ],
            ],
        ], 402); // 402 Payment Required
    }
}

