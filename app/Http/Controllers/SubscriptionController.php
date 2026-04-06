<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Handle a user subscribing to a new membership plan.
     * Redirects to payment page if plan has a price, otherwise activates free plan.
     *
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe(Membership $membership)
    {
        $user = Auth::user();

        // If free plan, activate directly
        if ($membership->price <= 0) {
            // Deactivate any existing active memberships for this user.
            DB::table('user_memberships')
                ->where('user_id', $user->id)
                ->where('is_active', 1)
                ->update(['is_active' => 0]);

            // Create the new active membership record.
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

            return redirect()->route('dashboard')->with('status', "You have successfully subscribed to the {$membership->name} plan!");
        }

        // For paid plans, redirect to payment page
        return redirect()->route('payment.create', $membership);
    }
}
