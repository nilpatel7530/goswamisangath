<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Membership;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\HighestQualification;
use App\Models\Education;
use App\Models\Occupation;
use App\Models\MotherTongueMaster;
use App\Models\Hobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality - search across multiple fields
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('mobile_number', 'like', "%{$searchTerm}%")
                  ->orWhere('id', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%")
                  ->orWhere('state', 'like', "%{$searchTerm}%")
                  ->orWhere('country', 'like', "%{$searchTerm}%")
                  ->orWhere('occupation', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by membership status
        if ($request->filled('membership_status')) {
            if ($request->membership_status === 'with_membership') {
                $query->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                      ->from('user_memberships')
                      ->whereColumn('user_memberships.user_id', 'users.id')
                      ->where('user_memberships.is_active', 1);
                });
            } elseif ($request->membership_status === 'without_membership') {
                $query->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                      ->from('user_memberships')
                      ->whereColumn('user_memberships.user_id', 'users.id')
                      ->where('user_memberships.is_active', 1);
                });
            }
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
        $allowedSortColumns = ['id', 'full_name', 'email', 'created_at', 'role'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort_order
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';
        
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Get active membership with expiry
        $activeMembership = DB::table('user_memberships')
            ->where('user_memberships.user_id', $user->id)
            ->where('user_memberships.is_active', 1)
            ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
            ->select('user_memberships.*', 'memberships.name as membership_name', 'memberships.price', 'memberships.visits_allowed', 'memberships.interest_limit')
            ->first();

        // Calculate days remaining if membership exists
        $daysRemaining = null;
        if ($activeMembership) {
            // Free plans (price = 0) never expire, so don't calculate days remaining
            if ($activeMembership->price == 0) {
                $daysRemaining = null; // Free plan never expires
            } elseif ($activeMembership->expires_at) {
                $expiresAt = Carbon::parse($activeMembership->expires_at);
                $daysRemaining = (int) round(now()->diffInDays($expiresAt, false)); // Round to nearest integer
            } elseif ($activeMembership->purchased_at) {
                // If no expiry date for paid plans, calculate from purchased_at + 30 days
                $expiresAt = Carbon::parse($activeMembership->purchased_at)->addDays(30);
                $daysRemaining = (int) round(now()->diffInDays($expiresAt, false)); // Round to nearest integer
            }
        }

        // Get all membership history
        $membershipHistory = DB::table('user_memberships')
            ->where('user_memberships.user_id', $user->id)
            ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
            ->select('user_memberships.*', 'memberships.name as membership_name', 'memberships.price')
            ->orderBy('user_memberships.created_at', 'desc')
            ->get();

        // Get user activity history
        $activityHistory = DB::table('user_activities')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // Get user update history from updated_at timestamps
        $updateHistory = [];
        if ($user->updated_at && $user->updated_at != $user->created_at) {
            $updateHistory[] = [
                'type' => 'Profile Updated',
                'description' => 'User profile information was updated',
                'date' => $user->updated_at,
            ];
        }

        // Add membership changes to history
        foreach ($membershipHistory as $membership) {
            $updateHistory[] = [
                'type' => 'Membership ' . ($membership->is_active ? 'Activated' : 'Deactivated'),
                'description' => $membership->membership_name . ' membership ' . ($membership->is_active ? 'activated' : 'deactivated'),
                'date' => $membership->is_active ? ($membership->created_at ?? $membership->purchased_at) : $membership->updated_at,
            ];
        }

        // Sort update history by date
        usort($updateHistory, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return view('admin.users.show', compact('user', 'activeMembership', 'daysRemaining', 'membershipHistory', 'activityHistory', 'updateHistory'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $memberships = Membership::all();
        
        // Lookup Data for Dropdowns
        $countries = Country::where('status', 1)->get();
        $highestQualifications = HighestQualification::where('status', 1)->get();
        $occupations = Occupation::where('status', 1)->get();
        $motherTongues = MotherTongueMaster::where('status', 1)->get();
        $allHobbies = Hobby::where('status', 1)->get();

        // Dependent Dropdowns (Initial Data)
        $states = collect();
        if ($user->country_id) {
            $states = State::where('country_id', $user->country_id)->where('status', 1)->get();
        }

        $cities = collect();
        if ($user->state_id) {
            $cities = City::where('state_id', $user->state_id)->where('status', 1)->get();
        }

        $educations = collect();
        if ($user->highest_education_id) {
            $educations = Education::where('highest_qualification_id', $user->highest_education_id)->where('status', 1)->get();
        }

        // Current User Hobbies
        $userHobbyIds = $user->hobbies()->pluck('hobbies.id')->toArray();

        // Get active subscription for the user
        $currentSubscription = DB::table('user_memberships')
            ->where('user_id', $user->id)
            ->where('is_active', 1)
            ->first();

        return view('admin.users.edit', compact(
            'user', 
            'memberships', 
            'currentSubscription',
            'countries', 
            'states', 
            'cities', 
            'highestQualifications', 
            'educations', 
            'occupations', 
            'motherTongues',
            'allHobbies',
            'userHobbyIds'
        ));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validate all incoming data, including profile fields and management fields
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['user', 'admin'])],
            'membership_id' => ['nullable', 'exists:memberships,id'],
            'birth_day' => ['required', 'integer', 'min:1', 'max:31'],
            'birth_month' => ['required', 'integer', 'min:1', 'max:12'],
            'birth_year' => ['required', 'integer', 'min:1950', 'max:' . (date('Y') - 18)],
            'height' => ['required', 'string'],
            'marital_status' => ['required', 'string'],
            'highest_education_id' => ['required', 'exists:highest_qualification_master,id'],
            'education_id' => ['required', 'exists:education_master,id'],
            'occupation_id' => ['required', 'exists:occupation_master,id'],
            'country_id' => ['required', 'exists:country_manage,id'],
            'state_id' => ['required', 'exists:state_master,id'],
            'city_id' => ['required', 'exists:city_master,id'],
            'mother_tongue_id' => ['required', 'exists:mothertongue_master,id'],
            'hobbies' => ['nullable', 'array'],
            'verification_status' => ['nullable', Rule::in(['not_uploaded', 'pending', 'verified', 'rejected'])],
            'residential_country' => ['nullable', 'string', 'max:255'],
            'residential_state' => ['nullable', 'string', 'max:255'],
            'residential_city' => ['nullable', 'string', 'max:255'],
            'residential_address' => ['nullable', 'string'],
            'working_country' => ['nullable', 'string', 'max:255'],
            'working_state' => ['nullable', 'string', 'max:255'],
            'working_city' => ['nullable', 'string', 'max:255'],
            'working_address' => ['nullable', 'string'],
        ];

        // Mobile number validation - only validate uniqueness if provided
        if ($request->filled('mobile_number')) {
            $rules['mobile_number'] = ['string', Rule::unique('users')->ignore($user->id)];
        } else {
            $rules['mobile_number'] = ['nullable', 'string'];
        }

        $request->validate($rules);

        // Prepare profile data for update - exclude non-user table fields
        $updateData = $request->except(['_token', '_method', 'membership_id', 'birth_day', 'birth_month', 'birth_year', 'hobbies', 'languages']);
        
        // Sync string fields for backward compatibility
        if ($request->country_id) {
            $updateData['country'] = Country::find($request->country_id)->name;
        }
        if ($request->state_id) {
            $updateData['state'] = State::find($request->state_id)->name;
        }
        if ($request->city_id) {
            $updateData['city'] = City::find($request->city_id)->city_master;
        }
        if ($request->highest_education_id) {
            $updateData['highest_education'] = HighestQualification::find($request->highest_education_id)->name;
        }
        if ($request->education_id) {
            $updateData['education_details'] = Education::find($request->education_id)->name;
        }
        if ($request->occupation_id) {
            $updateData['occupation'] = Occupation::find($request->occupation_id)->name;
        }
        if ($request->mother_tongue_id) {
            $updateData['mother_tongue'] = MotherTongueMaster::find($request->mother_tongue_id)->title;
        }

        // Combine date fields
        $updateData['dob'] = Carbon::createFromDate($request->birth_year, $request->birth_month, $request->birth_day)->format('Y-m-d');
        
        // Combine languages array into a string
        if ($request->has('languages')) {
            $updateData['languages_known'] = implode(',', $request->languages);
        } else {
            $updateData['languages_known'] = null;
        }
        
        // Update the user's main profile record
        $user->update($updateData);

        // Sync hobbies (Many-to-Many)
        if ($request->has('hobbies')) {
            $user->hobbies()->sync($request->hobbies);
        } else {
            $user->hobbies()->detach();
        }

        // --- Manage Subscription ---
        $newMembershipId = $request->input('membership_id');

        // Deactivate all existing memberships for this user first
        DB::table('user_memberships')
            ->where('user_id', $user->id)
            ->update(['is_active' => 0]);

        // If a new membership was selected (and it's not the "None" option)
        if ($newMembershipId) {
            // Create a new active subscription record
            DB::table('user_memberships')->insert([
                'user_id' => $user->id,
                'membership_id' => $newMembershipId,
                'is_active' => 1,
                'visits_used' => 0, // Reset visits count when admin assigns a new plan
                'purchased_at' => now(),
                'expires_at' => now()->addDays(30), // Set expiry to 30 days from now
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User profile and subscription updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userId = $user->id;

        // Manually delete all related records to avoid FK constraint errors
        // (in case cascade deletes are not configured on the live DB)
        DB::table('user_activities')->where('user_id', $userId)->delete();
        DB::table('user_interests')->where('sender_id', $userId)->orWhere('receiver_id', $userId)->delete();
        DB::table('profile_visits')->where('visitor_id', $userId)->orWhere('visited_id', $userId)->delete();
        DB::table('notifications')->where('user_id', $userId)->orWhere('related_user_id', $userId)->delete();
        DB::table('messages')->where('sender_id', $userId)->orWhere('receiver_id', $userId)->delete();
        DB::table('user_shortlists')->where('user_id', $userId)->orWhere('shortlisted_user_id', $userId)->delete();
        DB::table('user_blocks')->where('blocker_id', $userId)->orWhere('blocked_id', $userId)->delete();
        DB::table('reports')->where('reporter_id', $userId)->orWhere('reported_user_id', $userId)->delete();
        DB::table('payment_transactions')->where('user_id', $userId)->delete();
        DB::table('user_memberships')->where('user_id', $userId)->delete();
        DB::table('hobby_user')->where('user_id', $userId)->delete();

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}

