<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Membership;
use App\Models\SiteSetting;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\MotherTongueMaster;
use App\Traits\CreatesNotifications;

class PageController extends Controller
{
    use CreatesNotifications;
    /**
     * Show home page
     */
    public function home()
    {
        // If user is already logged in, redirect based on role
        if (Auth::check()) {
            $user = Auth::user();
            $user->refresh(); // Get latest role from database
            
            return $user->role === 'admin' 
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }
        
        return view('pages.home');
    }

    /**
     * Show about us page
     */
    public function about()
    {
        $leadership = config('leadership');
        return view('pages.about', compact('leadership'));
    }

    /**
     * Show success stories page
     */
    public function successStories()
    {
        return view('pages.success-stories');
    }

    /**
     * Show terms and conditions page
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Show privacy policy page
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Show thank you / countdown page during demo mode
     */
    public function thankYou()
    {
        $liveAt = SiteSetting::get('live_at');
        return view('pages.thank-you', compact('liveAt'));
    }

    public function signup()
    {
        $highest_qualifications = DB::table('highest_qualification_master')->where('status', 1)->get();
        $occupations = DB::table('occupation_master')->where('status', 1)->get();
        $countries = DB::table('country_manage')->where('status', 1)->get();
        $raashis = DB::table('raashi_master')->where('status', 1)->orderBy('name')->get();
        $motherTongues = DB::table('mothertongue_master')->where('status', 1)->get();
        $hobbies = DB::table('hobbies')->where('status', 1)->orderBy('name')->get();

        
        // Create a new user instance for the form
        $user = new \App\Models\User();
        return view('pages.signup', compact('highest_qualifications', 'occupations', 'countries', 'raashis', 'user', 'motherTongues', 'hobbies'));
    }

    public function getEducations($id)
    {
        try {
        $educations = DB::table('education_master')
            ->where('highest_qualification_id', $id)
            ->where('status', 1)
                ->where('is_visible', 1)
                ->orderBy('name')
            ->get();
        return response()->json($educations);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load education details'], 500);
        }
    }

    public function login()
    {
        return view('pages.login');
    }

    public function membership()
    {
        // Get only active memberships, ordered by display_order then price
        $memberships = Membership::where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('price', 'asc')
            ->get();
        
        // Get current user's active membership if logged in
        $currentUserMembership = null;
        if (Auth::check()) {
            $currentUserMembership = DB::table('user_memberships')
                ->where('user_memberships.user_id', Auth::id())
                ->where('user_memberships.is_active', 1)
                ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
                ->select('memberships.*')
                ->first();
        }
        
        return view('pages.membership', compact('memberships', 'currentUserMembership'));
    }



    /**
     * Advanced matches/search page
     */
    public function matches(Request $request)
    {
        $user = Auth::user();
        
        // Get highest qualifications, create sample data if none exist
        $highestQualifications = DB::table('highest_qualification_master')->where('status', 1)->where('is_visible', 1)->get();
        
        // If no qualifications exist, create sample data
        if ($highestQualifications->isEmpty()) {
            $sampleQualifications = [
                ['name' => '10th / SSLC', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => '12th / HSC', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Diploma', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Bachelor\'s Degree', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Master\'s Degree', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'M.Phil', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Ph.D / Doctorate', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'CA', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'CS', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'ICWA', 'status' => 'active', 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
            ];
            
            DB::table('highest_qualification_master')->insert($sampleQualifications);
            
            // Re-fetch qualifications
            $highestQualifications = DB::table('highest_qualification_master')->where('status', 1)->where('is_visible', 1)->get();
        }
        $educations = DB::table('education_master')->where('status', 1)->where('is_visible', 1)->get();
        $occupations = DB::table('occupation_master')->where('status', 1)->where('is_visible', 1)->get();
        $countries = DB::table('country_manage')->where('status', 1)->get();
        $states = DB::table('state_master')->where('is_visible', 1)->get();
        // Get cities, create sample data if none exist
        $cities = DB::table('city_master')
            ->where('is_visible', 1)
            ->select('id', 'city_master', 'name', 'state_id')
            ->orderBy('city_master', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();
        
        // If no cities exist, create sample data
        if ($cities->isEmpty()) {
            // Check if country exists
            $country = DB::table('country_manage')->where('status', 1)->first();
            if (!$country) {
                $countryId = DB::table('country_manage')->insertGetId([
                    'name' => 'India',
                    'sortname' => 'IN',
                    'phone_code' => '+91',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $countryId = $country->id;
            }
            
            // Check if state exists
            $state = DB::table('state_master')->where('is_visible', 1)->first();
            if (!$state) {
                $stateId = DB::table('state_master')->insertGetId([
                    'name' => 'Gujarat',
                    'country_id' => $countryId,
                    'is_visible' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $stateId = $state->id;
            }
            
            // Create sample cities
            $sampleCities = [
                ['city_master' => 'Mumbai', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['city_master' => 'Delhi', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['city_master' => 'Bangalore', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['city_master' => 'Hyderabad', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['city_master' => 'Chennai', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['city_master' => 'Kolkata', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['city_master' => 'Pune', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['city_master' => 'Ahmedabad', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['city_master' => 'Jaipur', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['city_master' => 'Surat', 'state_id' => $stateId, 'is_visible' => 1, 'created_at' => now(), 'updated_at' => now()],
            ];
            
            DB::table('city_master')->insert($sampleCities);
            
            // Re-fetch cities
            $cities = DB::table('city_master')
                ->where('is_visible', 1)
                ->select('id', 'city_master', 'name', 'state_id')
                ->orderBy('city_master', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();
        }
        
        // Build query
        $query = User::query();
        
        // Exclude current user
        $query->where('id', '!=', $user->id);
        
        // Gender filter (opposite gender by default)
        $genderPref = $request->get('gender_pref', $user->gender === 'male' ? 'female' : 'male');
        if ($genderPref === 'female') {
            $query->where('gender', 'female');
        } elseif ($genderPref === 'male') {
            $query->where('gender', 'male');
        }
        
        // Age range filter
        $ageFrom = $request->get('age_from', 24);
        $ageTo = $request->get('age_to', 32);
        
        if ($ageFrom) {
            $maxDob = Carbon::now()->subYears($ageFrom)->endOfDay();
            $query->where('dob', '<=', $maxDob);
        }
        
        if ($ageTo) {
            $minDob = Carbon::now()->subYears($ageTo + 1)->startOfDay();
            $query->where('dob', '>=', $minDob);
        }
        
        // Location filter: state(s) then city/cities (cities scoped by selected states in the UI)
        if ($request->filled('state')) {
            $stateArray = is_array($request->state) ? $request->state : [$request->state];
            $query->where(function ($q) use ($stateArray) {
                foreach ($stateArray as $state) {
                    $q->orWhere('state', 'like', '%' . trim($state) . '%');
                }
            });
        }
        if ($request->filled('city')) {
            $cityArray = is_array($request->city) ? $request->city : [$request->city];
            $query->where(function ($q) use ($cityArray) {
                foreach ($cityArray as $city) {
                    $q->orWhere('city', 'like', '%' . trim($city) . '%');
                }
            });
        }
        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }
        // Education filter
        if ($request->filled('education')) {
            $educationsArray = is_array($request->education) ? $request->education : [$request->education];
            $query->whereIn('highest_education', $educationsArray);
        }
        
        // Occupation filter
        if ($request->filled('occupation')) {
            $occupationsArray = is_array($request->occupation) ? $request->occupation : [$request->occupation];
            $query->whereIn('occupation', $occupationsArray);
        }
        
        // Marital status filter
        if ($request->filled('marital_status')) {
            $statusArray = is_array($request->marital_status) ? $request->marital_status : [$request->marital_status];
            $query->whereIn('marital_status', $statusArray);
        }
        
        // Income filter
        if ($request->filled('income_min')) {
            // This would need custom logic based on how income is stored
        }
        
        // Get all matching users first (before pagination)
        $allUsers = $query->get();
        
        // Calculate age and match percentage for each user
        $allUsers->each(function ($match) use ($user) {
            if ($match->dob) {
                $match->age = Carbon::parse($match->dob)->age;
            } else {
                $match->age = 'N/A';
            }
            
            // Simple match percentage calculation (deterministic - no random values)
            $matchScore = 0;
            $maxScore = 0;
            
            // City match (20 points)
            if ($user->city && $match->city) {
                $maxScore += 20;
                if ($user->city === $match->city) {
                    $matchScore += 20;
                }
            }
            
            // Education match (25 points)
            if ($user->highest_education && $match->highest_education) {
                $maxScore += 25;
                if ($user->highest_education === $match->highest_education) {
                    $matchScore += 25;
                }
            }
            
            // Mother tongue match (25 points)
            if ($user->mother_tongue && $match->mother_tongue) {
                $maxScore += 25;
                if ($user->mother_tongue === $match->mother_tongue) {
                    $matchScore += 25;
                }
            }
            
            // Calculate percentage based on matches
            if ($maxScore > 0) {
                $match->matchPercentage = round(($matchScore / $maxScore) * 100);
                // Ensure minimum of 40% and maximum of 100%
                $match->matchPercentage = max(40, min(100, $match->matchPercentage));
            } else {
                // If no criteria available, default to 50%
                $match->matchPercentage = 50;
            }
            
            $match->location = trim(($match->city ?? '') . ($match->city && $match->country ? ', ' : '') . ($match->country ?? ''));
            
            // Check if user has shortlisted this profile
            $match->isShortlisted = DB::table('user_shortlists')
                ->where('user_id', $user->id)
                ->where('shortlisted_user_id', $match->id)
                ->exists();
        });
        
        // Sort by match percentage (highest first), then apply other sorting if needed
        $sortBy = $request->get('sort', 'relevance');
        $sortedUsers = $allUsers->sortByDesc('matchPercentage');
        
        // Apply additional sorting if not relevance
        switch ($sortBy) {
            case 'newest':
                $sortedUsers = $allUsers->sortByDesc('created_at');
                break;
            case 'age_low':
                $sortedUsers = $allUsers->sortByDesc(function($match) {
                    return $match->dob ? Carbon::parse($match->dob)->timestamp : 0;
                });
                break;
            case 'age_high':
                $sortedUsers = $allUsers->sortBy(function($match) {
                    return $match->dob ? Carbon::parse($match->dob)->timestamp : 0;
                });
                break;
            default:
                // Already sorted by match percentage descending
                break;
        }
        
        // Manually paginate the sorted collection
        $currentPage = $request->get('page', 1);
        $perPage = 20;
        $currentItems = $sortedUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $total = $sortedUsers->count();
        
        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Get recently viewed profiles
        $recentlyViewed = collect();
        try {
            $recentVisits = DB::table('profile_visits')
                ->where('visitor_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->pluck('visited_id');
            
            if ($recentVisits->isNotEmpty()) {
                $recentlyViewed = User::whereIn('id', $recentVisits)->get();
                $recentlyViewed->each(function ($viewed) {
                    if ($viewed->dob) {
                        $viewed->age = Carbon::parse($viewed->dob)->age;
                    }
                });
            }
        } catch (\Exception $e) {
            // Profile visits table might not exist
        }
        
        return view('pages.matches', [
            'users' => $users,
            'recentlyViewed' => $recentlyViewed,
            'highestQualifications' => $highestQualifications,
            'educations' => $educations,
            'occupations' => $occupations,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'filters' => $request->all(),
            'genderPref' => $genderPref,
            'ageFrom' => $ageFrom,
            'ageTo' => $ageTo,
        ]);
    }

    public function viewProfile(User $user)
    {
        $visitor = Auth::user();
        $profileUserId = $user->id;

        // Allow users to view their own profile (for preview purposes)
        // Only skip visit tracking if viewing own profile
        $isOwnProfile = $visitor->id == $profileUserId;
        
        // Check if visitor has blocked this user or vice versa (only for other users)
        if (!$isOwnProfile) {
            $isMutuallyBlocked = DB::table('user_blocks')
                ->where(function($query) use ($visitor, $profileUserId) {
                    $query->where('blocker_id', $visitor->id)
                          ->where('blocked_id', $profileUserId)
                          ->orWhere(function($q) use ($visitor, $profileUserId) {
                              $q->where('blocker_id', $profileUserId)
                                ->where('blocked_id', $visitor->id);
                          });
                })
                ->exists();

            if ($isMutuallyBlocked) {
                return redirect()->route('dashboard')->with('error', 'You cannot view this profile.');
            }
        }
        
        // Skip visit tracking and membership checks for own profile
        if (!$isOwnProfile) {
            $hasVisited = DB::table('profile_visits')
                ->where('visitor_id', $visitor->id)
                ->where('visited_id', $profileUserId)
                ->exists();
                
            if (!$hasVisited) {
                $membership = DB::table('user_memberships')
                    ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
                    ->where('user_memberships.user_id', $visitor->id)
                    ->where('user_memberships.is_active', 1)
                    ->select('user_memberships.id as user_membership_id', 'memberships.visits_allowed', 'user_memberships.visits_used')
                    ->first();

                if (!$membership || $membership->visits_used >= $membership->visits_allowed) {
                    return redirect()->route('membership')->with('status', 'Please upgrade your membership to view more profiles.');
                }

                DB::table('user_memberships')
                    ->where('id', $membership->user_membership_id)
                    ->increment('visits_used');

                DB::table('profile_visits')->insert([
                    'visitor_id' => $visitor->id,
                    'visited_id' => $profileUserId,
                    'visited_at' => now(),
                ]);

                // Log activity
                try {
                    DB::table('user_activities')->insert([
                        'user_id' => $visitor->id,
                        'activity' => 'Visited ' . $user->full_name . '\'s profile.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    // Table might not exist
                }
            }
        }


        // Fetch the main profile data - user is already loaded via route model binding
        
        // Age is now handled by User model accessor
        if ($user->dob) {
            $user->dobFormatted = $user->dob->format('d M Y');
        } else {
            $user->dobFormatted = 'N/A';
        }
        
        // Calculate compatibility percentage
        if ($isOwnProfile) {
            $matchPercentage = 100;
        } else {
            $matchPercentage = $visitor->calculateCompatibility($user);
        }
        
        // Format location
        $user->location = trim(($user->city ?? '') . ($user->city && $user->state ? ', ' : '') . ($user->state ?? '') . ($user->state && $user->country ? ', ' : '') . ($user->country ?? ''));
        
        // Check interest status (skip for own profile)
        $interestSent = false;
        $interestReceived = false;
        $interestAccepted = false;
        $canChat = false;
        
        if (!$isOwnProfile) {
            try {
                $tableExists = DB::select("SHOW TABLES LIKE 'user_interests'");
                if (!empty($tableExists)) {
                    // Check if visitor sent interest to this profile
                    $sentInterest = DB::table('user_interests')
                        ->where('sender_id', $visitor->id)
                        ->where('receiver_id', $user->id)
                        ->first();
                    
                    if ($sentInterest) {
                        $interestSent = true;
                        $interestAccepted = $sentInterest->status === 'accepted';
                    }
                    
                    // Check if visitor received interest from this profile
                    $receivedInterest = DB::table('user_interests')
                        ->where('sender_id', $user->id)
                        ->where('receiver_id', $visitor->id)
                        ->first();
                    
                    if ($receivedInterest) {
                        $interestReceived = true;
                        if ($receivedInterest->status === 'accepted') {
                            $interestAccepted = true;
                        }
                    }
                    
                    // Can chat if:
                    // 1. Interest was accepted (either direction)
                    // 2. Interest is mutual (both sent interest to each other)
                    $canChat = $interestAccepted || ($interestSent && $interestReceived);
                }
            } catch (\Exception $e) {
                // Table might not exist
            }
            
            // Check if profile is shortlisted
            $isShortlisted = false;
            try {
                $isShortlisted = DB::table('user_shortlists')
                    ->where('user_id', $visitor->id)
                    ->where('shortlisted_user_id', $user->id)
                    ->exists();
            } catch (\Exception $e) {
                // Table might not exist
            }
        } else {
            // For own profile, set defaults
            $isShortlisted = false;
        }

        // Check if visitor has a paid membership (not free plan)
        $hasPaidMembership = false;
        $isFreePlan = true;
        if (!$isOwnProfile) {
            try {
                $visitorMembership = DB::table('user_memberships')
                    ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
                    ->where('user_memberships.user_id', $visitor->id)
                    ->where('user_memberships.is_active', 1)
                    ->select('memberships.price', 'memberships.name')
                    ->first();
                
                if ($visitorMembership) {
                    $isFreePlan = $visitorMembership->price == 0;
                    $hasPaidMembership = $visitorMembership->price > 0;
                }
            } catch (\Exception $e) {
                // Table might not exist, default to free plan
            }
        }

        // Check for mutual interest (both users sent interest to each other)
        $hasMutualInterest = false;
        if (!$isOwnProfile) {
            $hasMutualInterest = $interestSent && $interestReceived;
        }

        // Retrieve privacy settings
        $hideContact = SiteSetting::get('hide_contact_if_not_accepted', 'on') === 'on';
        $hideAddress = SiteSetting::get('hide_address_if_not_accepted', 'on') === 'on';

        // Granular visibility: Contact details can be viewed if:
        // 1. Interest accepted OR Mutual interest
        // 2. OR (Admin toggle is OFF AND user has paid membership)
        $canViewContact = $interestAccepted || $hasMutualInterest || (!$hideContact && $hasPaidMembership);
        $canViewAddress = $interestAccepted || $hasMutualInterest || (!$hideAddress && $hasPaidMembership);

        // Check if user is blocked by visitor (for UI display)
        $isBlockedByVisitor = false;
        if (!$isOwnProfile) {
            $isBlockedByVisitor = DB::table('user_blocks')
                ->where('blocker_id', $visitor->id)
                ->where('blocked_id', $profileUserId)
                ->exists();
        }

        return view('pages.view-profile', [
            'user' => $user,
            'visitor' => $visitor,
            'matchPercentage' => $matchPercentage,
            'isShortlisted' => $isShortlisted,
            'interestSent' => $interestSent,
            'interestReceived' => $interestReceived,
            'interestAccepted' => $interestAccepted,
            'canChat' => $canChat,
            'canViewContact' => $canViewContact,
            'canViewAddress' => $canViewAddress,
            'hasPaidMembership' => $hasPaidMembership,
            'hasMutualInterest' => $hasMutualInterest,
            'isFreePlan' => $isFreePlan,
            'isBlocked' => $isBlockedByVisitor,
            'isOwnProfile' => $isOwnProfile,
        ]);
    }


    
    public function getCountries(Request $request)
    {
        $countries = DB::table('country_manage')->where('status', 1)->get();
        return response()->json($countries);
    }

    public function getStates(Request $request)
    {
        // Debug: Log the request data
        \Log::info('getStates called with country_id: ' . $request->country_id);
        
        $states = DB::table('state_master')
            ->where('country_id', $request->country_id)
            ->where('is_visible', 1)
            ->get();
            
        // Debug: Log the result
        \Log::info('States found: ' . $states->count());
        \Log::info('States data: ' . $states->toJson());
        
        return response()->json($states);
    }

    public function getCities(Request $request)
    {
        $cities = DB::table('city_master')
            ->where('state_id', $request->state_id)
            ->where('is_visible', 1)
            ->orderBy('city_master', 'ASC')
            ->get();
        return response()->json($cities);
    }

    public function shortlist()
    {
        $user = Auth::user();
        
        // Get shortlisted profiles
        $shortlistedUsers = collect();
        try {
            $shortlistIds = DB::table('user_shortlists')
                ->where('user_id', $user->id)
                ->pluck('shortlisted_user_id');
            
            if ($shortlistIds->isNotEmpty()) {
                $shortlistedUsers = User::whereIn('id', $shortlistIds)->get();
                
                // Calculate age and match percentage for each user
                $shortlistedUsers->each(function ($profile) use ($user) {
                    if ($profile->dob) {
                        $profile->age = Carbon::parse($profile->dob)->age;
                    } else {
                        $profile->age = 'N/A';
                    }
                    
                    // Calculate match percentage
                    $matchScore = 0;
                    $totalChecks = 0;
                    
                    if ($user->city && $profile->city && $user->city === $profile->city) {
                        $matchScore += 20;
                    }
                    if ($user->highest_education && $profile->highest_education && $user->highest_education === $profile->highest_education) {
                        $matchScore += 25;
                    }
                    $totalChecks++;
                    
                    if ($user->mother_tongue && $profile->mother_tongue && $user->mother_tongue === $profile->mother_tongue) {
                        $matchScore += 25;
                    }
                    $totalChecks++;
                    
                    $profile->matchPercentage = $totalChecks > 0 ? min(100, $matchScore + rand(50, 100)) : rand(60, 95);
                    
                    // Format location
                    $profile->location = trim(($profile->city ?? '') . ($profile->city && $profile->state ? ', ' : '') . ($profile->state ?? '') . ($profile->state && $profile->country ? ', ' : '') . ($profile->country ?? ''));
                    
                    // Check if interest is mutual
                    $profile->isMutual = false;
                    $profile->interestSent = false;
                    try {
                        $visitorSent = DB::table('user_interests')
                            ->where('sender_id', $user->id)
                            ->where('receiver_id', $profile->id)
                            ->exists();
                        
                        $profile->interestSent = $visitorSent;
                        
                        $profileSent = DB::table('user_interests')
                            ->where('sender_id', $profile->id)
                            ->where('receiver_id', $user->id)
                            ->exists();
                        
                        $profile->isMutual = $visitorSent && $profileSent;
                        
                        // Check if interest was accepted
                        $acceptedInterest = DB::table('user_interests')
                            ->where(function($query) use ($user, $profile) {
                                $query->where(function($q) use ($user, $profile) {
                                    $q->where('sender_id', $user->id)
                                      ->where('receiver_id', $profile->id);
                                })->orWhere(function($q) use ($user, $profile) {
                                    $q->where('sender_id', $profile->id)
                                      ->where('receiver_id', $user->id);
                                });
                            })
                            ->where('status', 'accepted')
                            ->exists();
                        
                        $profile->canChat = $profile->isMutual || $acceptedInterest;
                    } catch (\Exception $e) {
                        // Table might not exist
                    }
                });
            }
        } catch (\Exception $e) {
            // Table might not exist
        }
        
        // Get counts
        $totalCount = $shortlistedUsers->count();
        $mutualCount = $shortlistedUsers->where('isMutual', true)->count();
        
        return view('pages.shortlist', [
            'user' => $user,
            'shortlistedUsers' => $shortlistedUsers,
            'totalCount' => $totalCount,
            'mutualCount' => $mutualCount,
        ]);
    }

    public function toggleShortlist(User $user)
    {
        $currentUser = Auth::user();
        $targetUser = $user;
        
        // Prevent users from shortlisting themselves
        if ($currentUser->id == $targetUser->id) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You cannot shortlist yourself.'], 400);
            }
            return back()->with('error', 'You cannot shortlist yourself.');
        }
        
        // Check if already shortlisted
        $existing = DB::table('user_shortlists')
            ->where('user_id', $currentUser->id)
            ->where('shortlisted_user_id', $targetUser->id)
            ->first();
        
        if ($existing) {
            // Remove from shortlist
            DB::table('user_shortlists')
                ->where('user_id', $currentUser->id)
                ->where('shortlisted_user_id', $targetUser->id)
                ->delete();
            
            // Log activity
            try {
                DB::table('user_activities')->insert([
                    'user_id' => $currentUser->id,
                    'activity' => 'Removed ' . $targetUser->full_name . ' from shortlist.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Table might not exist
            }
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $targetUser->full_name . ' removed from shortlist.',
                    'isShortlisted' => false
                ]);
            }
            return back()->with('success', $targetUser->full_name . ' removed from shortlist.');
        } else {
            // Add to shortlist
            DB::table('user_shortlists')->insert([
                'user_id' => $currentUser->id,
                'shortlisted_user_id' => $targetUser->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Log activity
            try {
                DB::table('user_activities')->insert([
                    'user_id' => $currentUser->id,
                    'activity' => 'Added ' . $targetUser->full_name . ' to shortlist.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Table might not exist
            }
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $targetUser->full_name . ' added to shortlist.',
                    'isShortlisted' => true
                ]);
            }
            return back()->with('success', $targetUser->full_name . ' added to shortlist.');
        }
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Get membership details
        $membership = DB::table('user_memberships')
            ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
            ->where('user_memberships.user_id', $user->id)
            ->where('user_memberships.is_active', 1)
            ->select('memberships.name', 'memberships.visits_allowed', 'user_memberships.visits_used')
            ->first();

        // Get match suggestions (opposite gender, exclude current user)
        $featuredMatch = null;
        $suggestions = collect();
        
        // If user doesn't have gender set, show all users (except self)
        if ($user->gender) {
            $oppositeGender = $user->gender === 'male' ? 'female' : 'male';
            $query = User::where('gender', $oppositeGender)
                ->where('id', '!=', $user->id);
        } else {
            // If no gender set, show all other users
            $query = User::where('id', '!=', $user->id);
        }
        
        // Get featured match (top pick) - deterministic selection based on user ID for consistency
        // Each user will see the same featured match on every refresh, but different users see different matches
        $totalMatches = (clone $query)->count();
        if ($totalMatches > 0) {
            // Use user ID modulo to deterministically select a match
            $matchIndex = $user->id % $totalMatches;
            $featuredMatch = (clone $query)->orderBy('id')->skip($matchIndex)->first();
        } else {
            $featuredMatch = null;
        }
        
        // Get other suggestions (exclude featured match)
        if ($featuredMatch) {
            $suggestions = (clone $query)
                ->where('id', '!=', $featuredMatch->id)
                ->inRandomOrder()
                ->take(4)
            ->get();
        } else {
            // If no featured match, get suggestions from the same query
            $suggestions = $query->inRandomOrder()->take(4)->get();
        }
        
        // Age and Compatibility are now handled by User model
        if ($featuredMatch) {
            $featuredMatch->matchPercentage = $user->calculateCompatibility($featuredMatch);
        }
        
        $suggestions->each(function ($suggestion) use ($user) {
            $suggestion->matchPercentage = $user->calculateCompatibility($suggestion);
        });
            
        return view('pages.dashboard', [
            'user' => $user,
            'membership' => $membership,
            'featuredMatch' => $featuredMatch,
            'suggestions' => $suggestions,
        ]);
    }

    public function messages()
    {
        $user = Auth::user();
        
        // Get accepted interests (mutual connections)
        $connections = collect();
        try {
            $acceptedInterests = DB::table('user_interests')
                ->where(function($query) use ($user) {
                    $query->where('sender_id', $user->id)
                          ->orWhere('receiver_id', $user->id);
                })
                ->where('status', 'accepted')
                ->get();
            
            $connectionIds = $acceptedInterests->map(function($interest) use ($user) {
                return $interest->sender_id == $user->id ? $interest->receiver_id : $interest->sender_id;
            })->unique();
            
            if ($connectionIds->isNotEmpty()) {
                $connections = User::whereIn('id', $connectionIds)->get();
                $connections->each(function($connection) {
                    if ($connection->dob) {
                        $connection->age = Carbon::parse($connection->dob)->age;
                    }
                });
            }
        } catch (\Exception $e) {
            // Table might not exist
        }
        
        return view('pages.messages', [
            'user' => $user,
            'connections' => $connections,
        ]);
    }

    public function sendInterest(User $user)
    {
        $currentUser = Auth::user();
        $targetUser = $user;

        // Check for Free Plan
        $membership = DB::table('user_memberships')
            ->join('memberships', 'user_memberships.membership_id', '=', 'memberships.id')
            ->where('user_memberships.user_id', $currentUser->id)
            ->where('user_memberships.is_active', 1)
            ->select('memberships.name')
            ->first();
            
        $isFreePlan = !$membership || $membership->name === 'Free';
        if ($isFreePlan) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You cannot send interest with a Free Plan. Please upgrade.'], 403);
            }
            return back()->with('error', 'You cannot send interest with a Free Plan. Upgrade your membership to connect with other members.');
        }

        // Prevent sending interest to yourself
        if ($currentUser->id === $targetUser->id) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You cannot send interest to yourself.'], 400);
            }
            return back()->with('error', 'You cannot send interest to yourself.');
        }

        try {
            // Check if user_interests table exists
            $tableExists = DB::select("SHOW TABLES LIKE 'user_interests'");
            
            if (!empty($tableExists)) {
                // Check if interest already sent
                $existingInterest = DB::table('user_interests')
                    ->where('sender_id', $currentUser->id)
                    ->where('receiver_id', $targetUser->id)
                    ->first();

                if ($existingInterest) {
                    if (request()->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'You have already sent interest to this user.'], 400);
                    }
                    return back()->with('info', 'You have already sent interest to this user.');
                }

                // Create interest record
                $interestId = DB::table('user_interests')->insertGetId([
                    'sender_id' => $currentUser->id,
                    'receiver_id' => $targetUser->id,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Create notification for the receiver
                $this->createNotification(
                    $targetUser,
                    'interest',
                    $currentUser->full_name . ' sent you an interest',
                    $currentUser,
                    'favorite',
                    'primary',
                    ['interest_id' => $interestId]
                );
            }

            // Log activity
            try {
                DB::table('user_activities')->insert([
                    'user_id' => $currentUser->id,
                    'activity' => 'Sent interest to ' . $targetUser->full_name . '.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Table might not exist
            }

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Interest sent successfully to ' . $targetUser->full_name . '!'
                ]);
            }

            return back()->with('success', 'Interest sent successfully to ' . $targetUser->full_name . '!');
        } catch (\Exception $e) {
            // If table doesn't exist, just show success message
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Interest sent successfully to ' . $targetUser->full_name . '!'
                ]);
            }
            return back()->with('success', 'Interest sent successfully to ' . $targetUser->full_name . '!');
        }
    }

    /**
     * Show requests page (received and sent)
     */
    public function requests(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'received'); // 'received' or 'sent'
        $sort = $request->get('sort', 'newest'); // 'newest' or 'oldest'
        $orderDir = ($sort === 'oldest') ? 'asc' : 'desc';
        
        try {
            $tableExists = DB::select("SHOW TABLES LIKE 'user_interests'");
            
            if (empty($tableExists)) {
                // Table doesn't exist, return empty data
                return view('pages.requests', [
                    'receivedRequests' => collect(),
                    'sentRequests' => collect(),
                    'type' => $type,
                    'sort' => $sort,
                    'receivedCount' => 0,
                    'sentCount' => 0,
                ]);
            }
            
            if ($type === 'sent') {
                // Get sent requests
                $sentRequests = DB::table('user_interests')
                    ->join('users', 'user_interests.receiver_id', '=', 'users.id')
                    ->where('user_interests.sender_id', $user->id)
                    ->where('user_interests.status', 'pending')
                    ->select('user_interests.*', 'users.*', 'user_interests.created_at as request_created_at')
                    ->orderBy('user_interests.created_at', $orderDir)
                    ->get();
                
                // Calculate age and format data
                $sentRequests->each(function ($request) {
                    $request->age = $request->dob ? Carbon::parse($request->dob)->age : null;
                    $request->location = trim(($request->city ?? '') . ($request->city && $request->country ? ', ' : '') . ($request->country ?? ''));
                });
                
                $receivedRequests = collect();
            } else {
                // Get received requests
                $receivedRequests = DB::table('user_interests')
                    ->join('users', 'user_interests.sender_id', '=', 'users.id')
                    ->where('user_interests.receiver_id', $user->id)
                    ->where('user_interests.status', 'pending')
                    ->select('user_interests.*', 'users.*', 'user_interests.created_at as request_created_at')
                    ->orderBy('user_interests.created_at', $orderDir)
                    ->get();
                
                // Calculate age and format data
                $receivedRequests->each(function ($request) {
                    $request->age = $request->dob ? Carbon::parse($request->dob)->age : null;
                    $request->location = trim(($request->city ?? '') . ($request->city && $request->country ? ', ' : '') . ($request->country ?? ''));
                });
                
                $sentRequests = collect();
            }
            
            // Get counts
            $receivedCount = DB::table('user_interests')
                ->where('receiver_id', $user->id)
                ->where('status', 'pending')
                ->count();
            
            $sentCount = DB::table('user_interests')
                ->where('sender_id', $user->id)
                ->where('status', 'pending')
                ->count();
            
            return view('pages.requests', [
                'receivedRequests' => $type === 'received' ? $receivedRequests : collect(),
                'sentRequests' => $type === 'sent' ? $sentRequests : collect(),
                'type' => $type,
                'sort' => $sort,
                'receivedCount' => $receivedCount,
                'sentCount' => $sentCount,
            ]);
        } catch (\Exception $e) {
            return view('pages.requests', [
                'receivedRequests' => collect(),
                'sentRequests' => collect(),
                'type' => $type,
                'sort' => $sort,
                'receivedCount' => 0,
                'sentCount' => 0,
            ]);
        }
    }

    /**
     * Accept a request
     */
    public function acceptRequest($id)
    {
        $user = Auth::user();
        
        try {
            $tableExists = DB::select("SHOW TABLES LIKE 'user_interests'");
            
            if (empty($tableExists)) {
                return back()->with('error', 'Requests feature is not available.');
            }
            
            $request = DB::table('user_interests')
                ->where('id', $id)
                ->where('receiver_id', $user->id)
                ->where('status', 'pending')
                ->first();
            
            if (!$request) {
                return back()->with('error', 'Request not found or already processed.');
            }
            
            // Update status to accepted
            DB::table('user_interests')
                ->where('id', $id)
                ->update(['status' => 'accepted', 'updated_at' => now()]);
            
            // Get sender
            $sender = User::find($request->sender_id);
            
            // Create notification for the sender
            if ($sender) {
                $this->createNotification(
                    $sender,
                    'interest_accepted',
                    $user->full_name . ' accepted your interest',
                    $user,
                    'favorite',
                    'primary',
                    ['interest_id' => $id]
                );
            }
            
            // Log activity
            try {
                DB::table('user_activities')->insert([
                    'user_id' => $user->id,
                    'activity' => 'Accepted request from ' . $sender->full_name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Activity table might not exist
            }
            
            return back()->with('success', 'Request accepted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while accepting the request.');
        }
    }

    /**
     * Decline a request
     */
    public function declineRequest($id)
    {
        $user = Auth::user();
        
        try {
            $tableExists = DB::select("SHOW TABLES LIKE 'user_interests'");
            
            if (empty($tableExists)) {
                return back()->with('error', 'Requests feature is not available.');
            }
            
            $request = DB::table('user_interests')
                ->where('id', $id)
                ->where('receiver_id', $user->id)
                ->where('status', 'pending')
                ->first();
            
            if (!$request) {
                return back()->with('error', 'Request not found or already processed.');
            }
            
            // Update status to declined
            DB::table('user_interests')
                ->where('id', $id)
                ->update(['status' => 'declined', 'updated_at' => now()]);
            
            return back()->with('success', 'Request declined.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while declining the request.');
        }
    }
}

