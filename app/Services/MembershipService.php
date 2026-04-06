<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MembershipService
{
    /**
     * Get or create the free membership plan.
     */
    public function getFreePlan(): Membership
    {
        return Membership::firstOrCreate(
            ['name' => 'Free', 'price' => 0],
            [
                'visits_allowed' => 0,
                'interest_limit' => 5, // Default for free plan
                'is_active' => true,
                'display_order' => 0,
                'badge' => 'Basic',
                'description' => 'Forever free with basic access.',
            ]
        );
    }

    /**
     * Assign free plan to a user.
     */
    public function assignFreePlan(User $user): void
    {
        // Check if user already has an active membership
        $hasActiveMembership = DB::table('user_memberships')
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->exists();

        if (!$hasActiveMembership) {
            $freePlan = $this->getFreePlan();
            
            // Check for Demo Mode Bonus
            $interestLimit = $freePlan->interest_limit;
            $demoMode = \App\Models\SiteSetting::get('demo_mode', 'off');
            if ($demoMode === 'on') {
                $bonusLimit = (int) \App\Models\SiteSetting::get('demo_bonus_interests_limit', 0);
                if ($bonusLimit > 0) {
                    $interestLimit = $bonusLimit;
                }
            }

            DB::table('user_memberships')->insert([
                'user_id' => $user->id,
                'membership_id' => $freePlan->id,
                'is_active' => 1,
                'visits_used' => 0,
                'interests_used' => 0,
                'interest_limit' => $interestLimit,
                'purchased_at' => now(),
                'expires_at' => null, // Free plan never expires
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Assign free plan to all users who don't have an active membership.
     */
    public function assignFreePlanToAllUsers(): int
    {
        $freePlan = $this->getFreePlan();

        // Get all user IDs that don't have an active membership
        $usersWithActiveMembership = DB::table('user_memberships')
            ->where('is_active', 1)
            ->pluck('user_id')
            ->toArray();

        $usersWithoutMembership = User::whereNotIn('id', $usersWithActiveMembership)->get();

        $count = 0;
        foreach ($usersWithoutMembership as $user) {
            DB::table('user_memberships')->insert([
                'user_id' => $user->id,
                'membership_id' => $freePlan->id,
                'is_active' => 1,
                'visits_used' => 0,
                'interests_used' => 0,
                'interest_limit' => $freePlan->interest_limit,
                'purchased_at' => now(),
                'expires_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $count++;
        }

        return $count;
    }
}

